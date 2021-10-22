build_phpstan_image:
	cd docker && docker build . -f phpstan.Dockerfile -t php-token-normalize/phpstan:latest && cd -

phpstan:
	docker run -v ${PWD}:/app --rm php-token-normalize/phpstan:latest analyse -c /app/build/config/phpstan.neon

phpunit:
	composer phpunit

test: phpstan
	composer testall
