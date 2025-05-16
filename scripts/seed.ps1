Write-Host "🌱 Menjalankan seeder..."
docker compose -f compose.dev.yaml exec app php artisan db:seed