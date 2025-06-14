#!/bin/bash

# Only wait for MySQL if DB_HOST is set AND not localhost (for local dev only)
if [ "$APP_ENV" != "production" ] && [ "$DB_HOST" != "127.0.0.1" ]; then
  echo "Waiting for MySQL at $DB_HOST:$DB_PORT..."
  until nc -z "$DB_HOST" "$DB_PORT"; do
    sleep 2
  done
fi

# Run Laravel setup if .env exists
if [ -f .env ]; then
  echo "Running Laravel setup commands..."
  php artisan config:clear
  php artisan config:cache
  php artisan key:generate
if [ ! -f database/database.sqlite ]; then
  touch database/database.sqlite
fi

  # Optional: use only if needed in production
  if [ "$APP_ENV" = "production" ]; then
    php artisan migrate --force
  fi
else
  echo "No .env file found — skipping Laravel setup."
fi

# Start Apache
exec apache2-foreground
