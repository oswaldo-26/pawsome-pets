#!/usr/bin/env sh
set -e

# Ensure SQLite is present when the app is configured for sqlite.
if [ -z "${DB_CONNECTION:-}" ] || [ "${DB_CONNECTION}" = "sqlite" ]; then
  mkdir -p database
  touch database/database.sqlite
fi

# Generate APP_KEY if not already set in environment.
if [ -z "${APP_KEY:-}" ]; then
  php artisan key:generate --force
fi

# Apply database migrations so the app has the correct schema.
php artisan migrate --force

# Start Laravel on the Railway-assigned port or default to 8000.
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
