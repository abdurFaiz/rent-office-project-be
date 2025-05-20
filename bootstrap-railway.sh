#!/bin/bash

# Setup environment for Railway
echo "Setting up environment for Railway deployment..."

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Set permissions
chmod -R 777 storage/
chmod -R 777 public/
chmod -R 777 bootstrap/cache/

# Create storage link if it doesn't exist
php artisan storage:link || true

# Export database configuration as environment variables
export DB_CONNECTION=pgsql
export DB_HOST="${RAILWAY_PRIVATE_DOMAIN}"
export DB_PORT=5432
export DB_DATABASE="${POSTGRES_DB}"
export DB_USERNAME="${POSTGRES_USER}"
export DB_PASSWORD="${POSTGRES_PASSWORD}"

# Try database connection
echo "Testing database connection..."
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connected successfully!'; } catch (\Exception \$e) { echo 'Database connection failed: ' . \$e->getMessage(); }"

# Run migrations (but continue if they fail)
php artisan migrate --force || echo "Migration failed, but continuing..."

# Start the server
php artisan serve --host=0.0.0.0 --port=${PORT:-8000} 