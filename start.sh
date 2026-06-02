#!/usr/bin/env sh
set -e
cd "$(dirname "$0")"

# Ensure environment file exists so Laravel commands can write APP_KEY.
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Ensure SQLite is present when the app is configured for sqlite.
if [ -z "${DB_CONNECTION:-}" ] || [ "${DB_CONNECTION}" = "sqlite" ]; then
  mkdir -p database
  touch database/database.sqlite
  if ! grep -q '^DB_DATABASE=' .env; then
    printf 'DB_DATABASE=database/database.sqlite\n' >> .env
  fi
fi

# Generate APP_KEY if not already set in environment.
if [ -z "${APP_KEY:-}" ]; then
  php artisan key:generate --force
  if [ -f .env ]; then
    export APP_KEY=$(grep '^APP_KEY=' .env | cut -d '=' -f2-)
  fi
fi

# Apply database migrations so the app has the correct schema.
php artisan migrate --force

# Start Laravel on the Railway-assigned port or default to 8000.
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
