<?php

declare(strict_types=1);

namespace Blog\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use function Zenstruck\Foundry\faker;

final class DatabaseContext implements Context
{
    public const PURGE_MESSENGER_TABLE = 'purge_messenger';

    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * @BeforeScenario
     */
    public function clearDatabase(BeforeScenarioScope $event): void
    {
        (new ORMPurger($this->em))->purge();

        if (in_array(self::PURGE_MESSENGER_TABLE, $event->getFeature()->getTags(), true)) {
            $connection = $this->em->getConnection();
            $truncate = $connection->getDatabasePlatform()->getTruncateTableSQL('messenger_messages');
            $connection->executeQuery($truncate);
        }
    }

    /**
     * @param TableNode<array> $nodes
     *
     * @Then /the table (?<table>[^ ]+) has (?<count>\d+) entr(?:y|ies) with the following values:/
     * @Then the table :table has no entry with the following values:
     */
    public function theTableHasCountEntriesWithValues(TableNode $nodes, string $table, int $count = 0): void
    {
        if ([] === $nodesTable = $nodes->getTable()) {
            throw new \LogicException('The nodes table cannot be empty.');
        }

        $query = sprintf('SELECT COUNT(1) FROM %s WHERE ', $table);

        $i = 0;
        $params = [];
        $query .= implode(' AND ', array_map(static function ($row) use (&$i, &$params) {
            [$key, $value] = $row;
            ++$i;
            if ('null' === $value) {
                return " $key = $value IS NULL";
            }
            $params['value_'.$i] = $value;

            return " CAST($key AS CHAR) = :value_$i ";
        }, $nodesTable));

        $databaseCount = (int) $this->em
            ->getConnection()
            ->executeQuery($query, $params)
            ->fetchOne()
        ;

        if ($count !== $databaseCount) {
            throw new \LogicException(sprintf('%d "%s" entries found but %d expected.', $databaseCount, $table, $count));
        }
    }

    /**
     * @Then /^the table (?<table>[^ ]+) has (?<count>\d+) entr(?:y|ies)$/
     */
    public function theTableHasCountEntries(string $table, int $count = 0): void
    {
        $query = sprintf('SELECT COUNT(1) FROM %s', $table);

        $databaseCount = (int) $this->em
            ->getConnection()
            ->executeQuery($query)
            ->fetchOne()
        ;

        if ($count !== $databaseCount) {
            throw new \LogicException(sprintf('%d "%s" entries found but %d expected.', $databaseCount, $table, $count));
        }
    }

    /**
     * @Given /^the table (?<table>[^ ]+) has the following values:$/
     * @Given /^the table (?<table>[^ ]+) has (?<count>\d+) rows with the following values:$/
     */
    public function insertInTable(TableNode $nodes, string $table, int $count = 1): void
    {
        if ([] === $nodesTable = $nodes->getTable()) {
            throw new \LogicException('The nodes table cannot be empty.');
        }

        for ($i = 0; $i < $count; ++$i) {
            $values = [];
            foreach ($nodesTable as $row) {
                [$column, $value, $type] = $row;

                if (in_array($type, ['string', 'text'], true)) {
                    $isText = 'text' === $type;
                    $values[] = sprintf(
                        ' %s = "%s"',
                        $column,
                        'fake' === $value ? faker()->text($isText ? 300 : 40) : $value,
                    );
                } elseif ('datetime' === $type) {
                    $values[] = sprintf(' %s = "%s"', $column, (new DateTimeImmutable($value))->format('Y-m-d h:i:s'));
                } else {
                    $values[] = sprintf(' %s = %s', $column, $value);
                }
            }
            $sql = sprintf('INSERT INTO %s SET %s;', $table, implode(', ', $values));

            $this->em->getConnection()->prepare($sql)->executeQuery();
        }
    }
}
