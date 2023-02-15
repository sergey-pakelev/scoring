CLIENT_SCORE_OPTIONS=$(if $(id),--id=$(id),)

dev:
	docker-compose build
	docker-compose up
stop:
	docker-compose stop
php-sh:
	docker-compose exec php sh
run-tests:
	docker-compose exec php bin/phpunit
fixtures:
	docker-compose exec php bin/console doctrine:fixtures:load -n
all-clients-scores:
	docker-compose exec php bin/console app:client-score
client-score:
	docker-compose exec php bin/console app:client-score $(CLIENT_SCORE_OPTIONS)
