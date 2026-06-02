#!/usr/bin/env sh
set -ex
cd "$(dirname "$0")"

echo "=== Starting Pawsome Pets on Railway ==="

# Ensure environment file exists so Laravel can boot.
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Ensure SQLite is configured and file exists.
if [ -z "${DB_CONNECTION:-}" ] || [ "${DB_CONNECTION}" = "sqlite" ]; then
  mkdir -p database storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
  touch database/database.sqlite
  if ! grep -q '^DB_DATABASE=' .env; then
    printf 'DB_DATABASE=database/database.sqlite\n' >> .env
  fi
fi

# Ensure APP_KEY exists and is not empty.
if ! grep -q '^APP_KEY=base64:' .env; then
  php artisan key:generate --force
fi

# Clear stale caches before startup.
php artisan optimize:clear

# Run migrations.
php artisan migrate --force

# Seed the database if there are no pets yet.
if [ "$(php -r 'require "vendor/autoload.php"; $app = require "bootstrap/app.php"; $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); $kernel->bootstrap(); echo \App\Models\Pet::count();')" = "0" ]; then
  php artisan db:seed --force
fi

# Ensure storage link and writable dirs so runtime uploads work
if [ -d public/storage ] && [ ! -L public/storage ]; then
  rm -rf public/storage
fi
php artisan storage:link || true
mkdir -p storage/app/public/pets
chmod -R 0777 storage bootstrap/cache public

# Start the PHP built-in server on the Railway-assigned port or default to 8080.
PORT="${PORT:-8080}"
exec php -S 0.0.0.0:"$PORT" -t public
