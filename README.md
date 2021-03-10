cp .env.example .env
composer install --no-dev
php artisan key:generate

// after changing .env
php artisan optimize