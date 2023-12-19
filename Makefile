init: build composer-install

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
