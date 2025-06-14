#!/bin/bash

# Wait for MySQL to be ready
until nc -z "$DB_HOST" "$DB_PORT"; do
  echo "Waiting for MySQL at $DB_HOST:$DB_PORT..."
  sleep 2
done

# Run Laravel setup commands only if .env exists
if [ -f .env ]; then
  echo "Running Laravel setup commands..."
  php artisan config:clear
  php artisan config:cache
  php artisan key:generate
  php artisan migrate --force
else
  echo "No .env file found — skipping Laravel setup."
fi

# Start Apache
exec apache2-foreground
