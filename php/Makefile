# Setup ————————————————————————————————————————————————————————————————————————————————————————————————————————————————
.SILENT:
.DEFAULT_GOAL := help

CONSOLE   = bin/console
COMPOSER  = composer
PHP       = php

##—— Makefile —————————————————————————————————————————————————————————————————————————————————————————————————————————
help: ## Outputs this help screen
	printf "${DE} \033[33mphp: ${PHP}  \nwith docker: $(IS_DOCKER)\n\n"
	@grep -E '(^[a-zA-Z0-9\.@_-]+:.*?##.*$$)|(^##)' $(firstword  $(MAKEFILE_LIST)) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

##—— Quality assurance ————————————————————————————————————————————————————————————————————————————————————————————————

format: vendor/autoload.php  ## Format code with php-cs-fixer
	$(PHP) vendor/bin/php-cs-fixer fix

phpstan: phpstan.neon.dist ## Run PHPStan (the configuration must be defined in phpstan.neon)
	$(PHP) vendor/bin/phpstan analyse  --xdebug --memory-limit 1G

psalm: psalm.xml ## Run Psalm
	$(PHP) vendor/bin/psalm  --no-cache

.PHONY: psalm phpstan format

qa-all:
	make format
	#make psalm
	make phpstan

##—— Composer —————————————————————————————————————————————————————————————————————————————————————————————————————————

check: ## Check dependencies
	$(PHP)  vendor/bin/composer-require-checker check composer.json

# rules based on files
composer.lock: composer.json
	$(COMPOSER) update --lock $(COMPOSER_OPTIONS)

vendor: composer.lock
	$(COMPOSER) install $(COMPOSER_OPTIONS)

##—— Install ——————————————————————————————————————————————————————————————————————————————————————————————————————————
install: vendor ## Install apps based on APP_ENV

##—— CONSOLE ——————————————————————————————————————————————————————————————————————————————————————————————————————————

commands:			## List CONSOLE commands
	$(CONSOLE)

##—— Challenge commands  ——————————————————————————————————————————————————————————————————————————————————————————————————————————

create: ## create a new puzzle directory
	$(CONSOLE) challenge:make $(filter-out $@,$(MAKECMDGOALS))

resolve: ## resolve a puzzle by name "make resole puzzleName"
	printf "$(CONSOLE) challenge:resolve $(filter-out $@,$(MAKECMDGOALS))\n"
	$(CONSOLE) challenge:resolve $(filter-out $@,$(MAKECMDGOALS))

resolve-test: ## resolve a puzzle in test mode (test.txt file) by name "make resole-test puzzleName"
	$(CONSOLE) challenge:resolve $(filter-out $@,$(MAKECMDGOALS)) --test

%:      # thanks to chakrit
    @:    # thanks to William Pursell