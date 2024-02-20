# Setup ————————————————————————————————————————————————————————————————————————————————————————————————————————————————
.SILENT:
.DEFAULT_GOAL := help


PHP_IMAGE_NAME = 'php_condingup'
##—— Makefile —————————————————————————————————————————————————————————————————————————————————————————————————————————
help: ## Outputs this help screen
	printf "${DE} \033[33mphp: ${PHP}  \nwith docker: $(IS_DOCKER)\n\n"
	@grep -E '(^[a-zA-Z0-9\.@_-]+:.*?##.*$$)|(^##)' $(firstword  $(MAKEFILE_LIST)) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

##—— Docker  ——————————————————————————————————————————————————————————————————————————————————————————————————————————

.PHONY: build deploy start stop logs restart shell up rm help exec ps

build-php:			## Build The Image
	docker build -t codingup:php --target php_codingup ./php

run-php-bash:			## Enter container bash
	docker run --rm -it -v $(PWD)/php:/srv/app codingup:php /bin/bash

images: ## List codingup docker images
	docker images codingup

