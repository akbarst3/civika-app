@echo off
echo Installing Laravel Breeze...
docker-compose -f compose.dev.yaml exec app composer require laravel/breeze --dev
docker-compose -f compose.dev.yaml exec app php artisan breeze:install blade
echo Laravel Breeze installed. Run npm install in local if prompted.
pause