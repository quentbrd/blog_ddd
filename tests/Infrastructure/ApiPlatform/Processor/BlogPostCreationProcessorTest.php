<?php

namespace Blog\Tests\Infrastructure\ApiPlatform\Processor;

use ApiPlatform\Metadata\Get;
use Blog\Application\Query\FindAuthorByUuidQuery;
use Blog\Domain\Command\CommandBusInterface;
use Blog\Domain\Exception\EntityCreationException;
use Blog\Domain\Exception\RelatedEntityNotFoundException;
use Blog\Domain\Query\QueryBusInterface;
use Blog\Infrastructure\ApiPlatform\Input\BlogPostCreationInput;
use Blog\Infrastructure\ApiPlatform\Processor\BlogPostCreationProcessor;
use Blog\Infrastructure\ApiPlatform\Resource\BlogPostResource;
use Blog\Infrastructure\ApiPlatform\Resource\BlogPostResourceFactory;
use Blog\Infrastructure\ApiPlatform\View\AuthorViewFactory;
use Blog\Infrastructure\Foundry\Factory\AuthorFactory;
use Blog\Infrastructure\Foundry\Factory\BlogPostFactory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zenstruck\Foundry\Test\Factories;

class BlogPostCreationProcessorTest extends TestCase
{
    use Factories;

    public function testProcess(): void
    {
        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects(self::once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList())
        ;

        $queryBus = $this->createMock(QueryBusInterface::class);
        $queryBus
            ->expects(self::once())
            ->method('ask')
            ->with(self::isInstanceOf(FindAuthorByUuidQuery::class))
            ->willReturn($author = AuthorFactory::createOne()->object())
        ;

        $commandBus = $this->createMock(CommandBusInterface::class);
        $commandBus
            ->expects(self::once())
            ->method('dispatch')
            ->willReturn(BlogPostFactory::createOne(['title' => 'Great title'])->object())
        ;

        $input = new BlogPostCreationInput(
            'Great title',
            'content',
            'summary',
            (string) $author->getUuid(),
        );

        $return = (new BlogPostCreationProcessor(
            $validator,
            $commandBus,
            $queryBus,
            new BlogPostResourceFactory(new AuthorViewFactory()))
        )->process($input, new Get());

        self::assertInstanceOf(BlogPostResource::class, $return);
        self::assertEquals('Great title', $return->title);
    }

    public function testProcessWithInvalidData(): void
    {
        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects(self::once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList([new ConstraintViolation('violation', null, [], '', null, null)]))
        ;

        $queryBus = $this->createMock(QueryBusInterface::class);
        $queryBus
            ->expects(self::never())
            ->method('ask')
        ;

        $commandBus = $this->createMock(CommandBusInterface::class);
        $commandBus
            ->expects(self::never())
            ->method('dispatch')
        ;

        $input = new BlogPostCreationInput(
            'Great title',
            'content',
            'summary',
            Uuid::uuid4()->toString(),
        );

        self::expectException(EntityCreationException::class);

        (new BlogPostCreationProcessor(
            $validator,
            $commandBus,
            $queryBus,
            new BlogPostResourceFactory(new AuthorViewFactory()))
        )->process($input, new Get());
    }

    public function testProcessWithAuthorNotFound(): void
    {
        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects(self::once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList())
        ;

        $queryBus = $this->createMock(QueryBusInterface::class);
        $queryBus
            ->expects(self::once())
            ->method('ask')
            ->with(self::isInstanceOf(FindAuthorByUuidQuery::class))
            ->willReturn(null)
        ;

        $commandBus = $this->createMock(CommandBusInterface::class);
        $commandBus
            ->expects(self::never())
            ->method('dispatch')
        ;

        $input = new BlogPostCreationInput(
            'Great title',
            'content',
            'summary',
            Uuid::uuid4()->toString(),
        );

        self::expectException(RelatedEntityNotFoundException::class);

        (new BlogPostCreationProcessor(
            $validator,
            $commandBus,
            $queryBus,
            new BlogPostResourceFactory(new AuthorViewFactory()))
        )->process($input, new Get());
    }
}
