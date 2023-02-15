#!/bin/sh
set -e

composer install

echo "Waiting for db to be ready"
ATTEMPTS_LEFT_TO_REACH_DATABASE=600
until [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ] || DATABASE_ERROR=$(bin/console doctrine:query:sql "SELECT 1" 2>&1); do
    sleep 1
    ATTEMPTS_LEFT_TO_REACH_DATABASE=$((ATTEMPTS_LEFT_TO_REACH_DATABASE - 1))
    echo "Still waiting for db to be ready. $ATTEMPTS_LEFT_TO_REACH_DATABASE attempts left"
done

if [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ]; then
    echo "The database is not up or not reachable:"
    echo "$DATABASE_ERROR"
    exit 1
else
    echo "The db is now ready and reachable"
fi

if ls -A migrations/*.php >/dev/null 2>&1; then
    echo "Applying migrations"
    bin/console doctrine:migrations:migrate -vvv --no-interaction
    echo "Migrations applied!"
fi

chown -R www-data:www-data var
exec docker-php-entrypoint "$@"
