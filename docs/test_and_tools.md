# Blog Service
[Back](../README.md#going-further)

## Tests and tooling

### Code static analysis tools
- `make phpstan`: Run a [phpstan](https://github.com/phpstan/phpstan) static analysis over the `src` and `tests` directories.
- `make deptrac`: Run a [deptrac](https://github.com/qossmic/deptrac) dependencies analysis. 
   This will check code dependencies to ensure that [application layers and bounded contexts](./code_architecture.md) are valid.

### Code syntax analysis tools
- `make php-cs-fixer`: Run [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) in order to check that the code syntax respect project's standards.
- `make fix-cs`: Run [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) to automatically fix code syntax issues that can be fixed.

### Tests
- `make behat`: Run [behat](https://github.com/Behat/Behat) tests (see next section for details).
- `make behat-wip`: Run [behat](https://github.com/Behat/Behat) tests marked with `@wip` tags.
- `make phpunit`: Run [phpunit](https://github.com/sebastianbergmann/phpunit) tests.
- `make phpunit-wip`: Run [phpunit](https://github.com/sebastianbergmann/phpunit) tests marked with `@wip` tags.
- `make ci`: Run every tests that'll ran in [CI](./ci_cd.md), such as syntax analysis, static analysis and tests.

#### Behat tests
We are using behat to test the application in real conditions. 
Indeed, we are running real HTTP calls on a specific server that is booting Symfony in test environment.

The main advantage (except the fact that we are testing the application in real conditions) is that tests are self explanatory.
Such as a product owner could actually read and understand those tests. For example:
```
Feature:
  In order to display data
  As an API consumer
  I want to have a access to agent reason

  Scenario: I can have access to list of agent reasons
    Given there is 20 agent reasons
    When I request "/api/agent_reasons"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
      "@type": "hydra:Collection",
      "hydra:totalItems": 20,
    }
    """
```

Under the hood, steps like `Given there is X agent reasons` are converted to PHP code that'll either setting up the application or testing it.
You can of course add your own steps (have a look at `Returns\Tests\Infrastructure\ReturnLabel\Behat\Context\ReturnLabelContext`)

Additional steps mostly used to test the API are provided by the [Behat API Exception](https://github.com/imbo/behat-api-extension), have a look at the [documentation](https://behat-api-extension.readthedocs.io/en/latest/)

When writing behat tests, you must be aware that database is cleaned up between scenarii.
Therefore at the beginning of each scenario, you must setup the application (and its database) in the state needed to test behaviors.
This enforce tests to be self explanatory.

As written before, the application ran and tested is running under a test environment.
This allows us to mock every external call and predict responses.
You can have a look for example at `Returns\Infrastructure\Legacy\Symfony\HttpClient\MockLegacyHttpClient`

Last but not least, there is no test database yet.
Therefore be aware that if you run behat tests, it'll wipe out your local database.
Of course you'll be able to refill it with fixtures (see the very next section).

### Database
- `make db-create`: Create/Recreate the database
- `make db-migrate`: Run doctrine migrations that are located in `migrations` folder.
  These migrations can be generated thanks to the `doctrine:migrations:diff` Symfony command
- `make db-fixtures`: Load database fixtures using classes located in `src/Infrastructure/*/Doctrine/Fixtures`
  Fixtures are generated thanks to [doctrine fixtures bundle](https://github.com/doctrine/DoctrineFixturesBundle) 
  and their data are randomly generated thanks to [zenstruck foundry bundle](https://github.com/zenstruck/foundry)
- `make db-reset`: Fully reset the database by sequentially run `db-create`, `db-migrate` and `db-fixtures`
