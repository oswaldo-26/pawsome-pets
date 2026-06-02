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

if [ "$(php -r 'require "vendor/autoload.php"; $app = require "bootstrap/app.php"; $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); $kernel->bootstrap(); echo \App\Models\Pet::count();')" = "0" ]; then
  php artisan db:seed --force
fi

php artisan optimize:clear
# Ensure storage link and writable dirs so runtime uploads work
if [ -d public/storage ] && [ ! -L public/storage ]; then
  rm -rf public/storage
fi
php artisan storage:link || true
mkdir -p storage/app/public/pets
chmod -R 0777 storage bootstrap/cache public

# Ensure PORT is defined and start the PHP server.
PORT="${PORT:-8080}"
exec php -S 0.0.0.0:"$PORT" -t public
