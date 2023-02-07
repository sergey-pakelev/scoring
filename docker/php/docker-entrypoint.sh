#!/bin/sh
set -e

composer install
chown -R www-data:www-data var
exec docker-php-entrypoint "$@"
