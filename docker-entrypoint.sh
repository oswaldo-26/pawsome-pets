#!/usr/bin/env sh
set -e
cd /var/www/html

if [ ! -f .env ]; then
  if [ -f .env.example ]; then
    cp .env.example .env
  else
    touch .env
  fi
fi

if [ -z "${DB_CONNECTION:-}" ] || [ "${DB_CONNECTION}" = "sqlite" ]; then
  mkdir -p database storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
  touch database/database.sqlite
  if ! grep -q '^DB_DATABASE=' .env; then
    printf 'DB_DATABASE=database/database.sqlite\n' >> .env
  fi
fi

if ! grep -q '^APP_KEY=base64:' .env; then
  php artisan key:generate --force
fi

php artisan migrate --force
php artisan optimize:clear

# Ensure PORT is defined and start the PHP server.
PORT="${PORT:-8080}"
exec php -S 0.0.0.0:"$PORT" -t public
