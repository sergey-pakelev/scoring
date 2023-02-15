#!/bin/sh
set -e

composer install
bin/console doctrine:migrations:migrate

chown -R www-data:www-data var
exec docker-php-entrypoint "$@"
