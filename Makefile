.PHONY: build_phpstan_image
build_phpstan_image:
	cd docker && docker build . -f phpstan.Dockerfile -t php-token-normalize/phpstan:latest --build-arg VERSION=1.8.5-php$(php_version) && cd -

.PHONY: phpstan
phpstan:
	docker run -v ${PWD}:/app --rm php-token-normalize/phpstan:latest analyse -c /app/build/config/phpstan.neon

.PHONY: unit
unit:
	composer phpunit

.PHONY: test
test: phpstan
	composer testall

.PHONY: psalm
psalm:
	composer psalm

.PHONY: infection
infection:
	composer infection

.PHONY: infection-after-phpunit
infection-after-phpunit:
	composer infection-after-phpunit
