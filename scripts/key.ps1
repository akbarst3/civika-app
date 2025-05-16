Write-Host "🔐 Menggenerate app key..."
docker compose -f compose.dev.yaml exec app php artisan key:generate
