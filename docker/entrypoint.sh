#!/bin/sh
set -e

echo "Waiting for database connection..."
if [ -n "$DB_HOST" ]; then
    until php -r "
        \$p = new PDO(
            'mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: 3306),
            getenv('DB_USERNAME'), getenv('DB_PASSWORD')
        );
    " 2>/dev/null; do
        echo "Database not ready, retrying in 3s..."
        sleep 3
    done
    echo "Database is ready."
fi

cd /var/www/html

# Bootstrap .env if it does not exist
if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env

    # Inject DB credentials from container environment
    sed -i "s/^APP_ENV=.*/APP_ENV=${APP_ENV:-production}/" .env
    sed -i "s|^APP_URL=.*|APP_URL=${APP_URL:-http://localhost:8080}|" .env
    sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=mysql/" .env
    sed -i "s/^DB_HOST=.*/DB_HOST=${DB_HOST:-db}/" .env
    sed -i "s/^DB_PORT=.*/DB_PORT=${DB_PORT:-3306}/" .env
    sed -i "s/^DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE:-job_portal}/" .env
    sed -i "s/^DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME:-job_portal}/" .env
    sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD:-secret}/" .env

    # Local uploads stay on the container filesystem
    sed -i "s/^FILESYSTEM_DISK=.*/FILESYSTEM_DISK=public/" .env

    chown www-data:www-data .env
fi

# Generate app key if missing
if ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
    echo "Generating application key..."
    php artisan key:generate --force --no-interaction
fi

# Laravel bootstrap
php artisan storage:link --force 2>/dev/null || true
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true
php artisan migrate --force --no-interaction 2>/dev/null || true
echo "Laravel bootstrap complete."

exec /usr/bin/supervisord -c /etc/supervisord.conf