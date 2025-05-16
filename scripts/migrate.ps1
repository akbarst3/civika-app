Write-Host "🛠️ Menjalankan migrasi database..."
docker compose -f compose.dev.yaml exec app php artisan migrate