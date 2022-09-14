ARG VERSION=1.8.5-php8.1

FROM ghcr.io/phpstan/phpstan:$VERSION
RUN composer global require spaze/phpstan-disallowed-calls