.PHONY: start stop init build tests

start:
	docker-compose up -d

stop:
	docker-compose stop

init:
	docker-compose build
	docker-compose up -d
	docker-compose exec php composer install
	docker-compose exec php php bin/console doctrine:database:create --if-not-exists
	docker-compose exec php php bin/console doctrine:migrations:migrate --no-interaction
	docker-compose exec php php bin/console doctrine:fixtures:load --no-interaction

build:
	build/build.sh

tests:
	docker-compose exec php php bin/console doctrine:database:create --if-not-exists --env=test
	docker-compose exec php php bin/console doctrine:migrations:migrate -n -q --env=test
	docker-compose exec php php bin/console doctrine:fixtures:load --no-interaction --env=test
	docker-compose exec php php vendor/bin/simple-phpunit