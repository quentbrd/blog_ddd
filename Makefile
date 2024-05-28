.DEFAULT_GOAL := help

SYMFONY = bin/console
COMPOSER = composer

RED := $(shell tput -Txterm setaf 1)
GREEN  := $(shell tput -Txterm setaf 2)
YELLOW := $(shell tput -Txterm setaf 3)
RESET  := $(shell tput -Txterm sgr0)
TARGET_MAX_CHAR_NUM=30

help:
	@echo "${GREEN}Blog${RESET}"
	@awk '/^[a-zA-Z\-\0-9]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")-1); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf "  ${GREEN}%-$(TARGET_MAX_CHAR_NUM)s${RESET} %s\n", helpCommand, helpMessage; \
		} \
		isTopic = match(lastLine, /^###/); \
	    if (isTopic) { \
			topic = substr($$1, 0, index($$1, ":")-1); \
			printf "\n${YELLOW}%s${RESET}\n", topic; \
		} \
	} { lastLine = $$0 }' $(MAKEFILE_LIST)


#################################
Database:

## Create/Recreate the database
db-create:
	@$(SYMFONY) doctrine:database:drop --force --if-exists -nq
	@$(SYMFONY) doctrine:database:create -nq

## Run database migrations
db-migrate:
	@$(SYMFONY) doctrine:migrations:migrate -nq --allow-no-migration

## Load database fixtures
db-fixtures:
	@$(SYMFONY) doctrine:fixtures:load -nq

## Reset database
db-reset: db-create db-migrate db-fixtures

.PHONY: db-create db-migrate db-fixtures db-reset

#################################
Tests:

## Run phpunit tests
phpunit:
	@ bin/phpunit --configuration phpunit.xml.dist

## Run phpunit WIP tests
phpunit-wip:
	@ bin/phpunit --configuration phpunit.xml.dist --group="wip"

## Run behat tests
behat: db-create db-migrate
	@ vendor/bin/behat -n --strict --format=progress --config behat.yaml

## Run behat WIP tests
behat-wip:
	@ vendor/bin/behat -n --strict --format=progress --config behat.yaml --tags="@wip"

## Run static analysis
phpstan:
	@ vendor/bin/phpstan analyze -c phpstan.neon --memory-limit 1G

## Run layers depedencies analysis
deptrac:
	@echo "\n\e[7mChecking DDD layers...\e[0m"
	@ vendor/bin/deptrac analyze --fail-on-uncovered --report-uncovered --no-progress --cache-file .deptrac_ddd.cache --config-file deptrac_ddd.yaml

	@echo "\n\e[7mChecking Bounded context layers...\e[0m"
	@ vendor/bin/deptrac analyze --fail-on-uncovered --report-uncovered --no-progress --cache-file .deptrac_bc.cache --config-file deptrac_bc.yaml

## Run codestyle analysis
php-cs-fixer:
	@ vendor/bin/php-cs-fixer fix --config .php-cs-fixer.dist.php --cache-file .php-cs-fixer.cache --dry-run --diff

## Run security analysis
security:
	@ composer audit

## Run schema mapping analysis
validate-schema:
	@ doctrine:schema:validate --skip-sync

## Search for forgotten @wip tag
search-for-wip:
	$(eval WIPS = $(shell find tests -name '*.feature' -exec grep -l "@wip" {} \;))

	@if [ ! -z $(WIPS) ]; then \
		echo "${RED}\"@wip\" found in:\n${RESET}"; \
		echo "$(WIPS)" | tr " " "\n"; \
		echo ""; \
		exit 1; \
	fi

	$(eval WIPS = $(shell find tests -name '*.php' -exec grep -l "@group wip" {} \;))

	@if [ ! -z $(WIPS) ]; then \
		echo "${RED}\"@group wip\" found in:\n${RESET}"; \
		echo "$(WIPS)" | tr " " "\n"; \
		echo ""; \
		exit 1; \
	fi

## Run a complete static analysis
static-analysis: search-for-wip php-cs-fixer phpstan deptrac security validate-schema

## Run every test
tests: phpunit behat

## Run either static analysis and tests
ci: static-analysis tests

.PHONY: phpunit phpunit-wip behat behat-wip phpstan deptrac php-cs-fixer security validate-schema static-analysis search-for-wip tests ci

#################################
Tools:

## Fix PHP files to be compliant with coding standards
fix-cs:
	@ vendor/bin/php-cs-fixer fix --config .php-cs-fixer.dist.php  --cache-file .php-cs-fixer.cache

## Clear Symfony cache
cc:
	@$(SYMFONY) cache:clear -e dev
	@$(SYMFONY) doctrine:cache:clear-metadata --flush -e dev
	@$(SYMFONY) cache:clear -e test
	@$(SYMFONY) doctrine:cache:clear-metadata --flush -e test

## Transform yaml config files to PHP
config-php:
	vendor/bin/config-transformer convert *