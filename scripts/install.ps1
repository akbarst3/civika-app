Write-Host "📦 Menjalankan composer install..."
docker compose -f compose.dev.yaml exec app composer install --no-interaction --optimize-autoloader
