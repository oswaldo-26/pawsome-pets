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

# Run migrations.
php artisan migrate --force

# Start the PHP built-in server on the Railway-assigned port or default to 8080.
exec php -S 0.0.0.0:"${PORT:-8080}" -t public public/index.php
