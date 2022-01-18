.PHONY: *
.DEFAULT_GOAL := help

DOCKER ?= false
PHP ?= php
COMPOSER ?= composer
OPTS=

ifeq ($(DOCKER), true)
	COMPOSER = ./docker/composer
	PHP = ./docker/php
endif

help:
	@printf "\n\033[33mAvailable Targets:\033[0m\n\n"
	@grep -E '^[-a-zA-Z0-9_\.\/]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%-15s\033[0m %s\n", $$1, $$2}'

lint-php: ## linting php files
	 if find . -name "*.php" -not -path "./vendor/*" -exec ${PHP} -l {} \; | grep -v "No syntax errors detected"; then exit 1; fi

deps: ## Install Composer deps
	${COMPOSER} install

tests: ## Run PHPUnit Tests
	${PHP} vendor/bin/phpunit

build: lint-php
