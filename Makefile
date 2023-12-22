init: build composer-install up fixtures

build:
	docker-compose build

composer-install:
	docker-compose run --rm php-fpm composer install

composer-update:
	docker-compose run --rm php-fpm composer update

up:
	docker-compose up -d

down:
	docker-compose down --remove-orphans

fixtures:
	docker compose run --rm php-fpm php bin/console doctrine:fixtures:load --no-interaction

test-all:
	docker compose run --rm php-fpm composer test

test-unit:
	docker compose run --rm php-fpm composer test -- --testsuite=unit

test-update:
	docker compose run --rm php-fpm composer test-update -- --testsuite=unit

test-unit-coverage:
	docker compose run --rm php-fpm composer test-coverage -- --testsuite=unit

psalm-check:
	docker compose run --rm php-fpm ./vendor/bin/psalm

