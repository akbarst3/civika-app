@echo off
echo 🚀 Menyalakan container Laravel dan service...
docker compose -f compose.dev.yaml up -d
timeout /t 5 >nul
docker compose -f compose.dev.yaml exec app php artisan serve --host=0.0.0.0 --port=8000