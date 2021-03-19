.PHONY: *
.DEFAULT_GOAL := help

PHP ?= php
OPTS=

help:
	@printf "\n\033[33mAvailable Targets:\033[0m\n\n"
	@grep -E '^[-a-zA-Z0-9_\.\/]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%-15s\033[0m %s\n", $$1, $$2}'

lint-php: ## linting php files
	 if find . -name "*.php" -not -path "./vendor/*" -exec ${PHP} -l {} \; | grep -v "No syntax errors detected"; then exit 1; fi

build: lint-php
