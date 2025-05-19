$SERVICE_NAME = "app"
$CONTAINER_NAME = "civika-app"
$COMPOSE_FILE = "compose.dev.yaml"

Write-Host "🚀 Memulai setup aplikasi Civika dengan Docker..."

# Cek .env
if (-not (Test-Path -Path ".env")) {
    Write-Host "📄 Menyalin .env.example ke .env..."
    Copy-Item -Path ".env.example" -Destination ".env"
} else {
    Write-Host "✅ .env sudah ada."
}

# Build & up docker-compose
Write-Host "🐳 Menjalankan docker compose..."
docker compose -f $COMPOSE_FILE up --build -d

# Tunggu container Civika siap
Write-Host "⏳ Menunggu container Civika siap..."
do {
    $status = docker inspect -f '{{.State.Status}}' $CONTAINER_NAME 2>$null
    Start-Sleep -Seconds 2
} while ($status -ne "running")
Write-Host "✅ Container $CONTAINER_NAME aktif!"

# Cek folder vendor
if (-not (Test-Path -Path "vendor")) {
    Write-Host "📦 Folder vendor belum ada, menjalankan composer install..."
    docker compose -f $COMPOSE_FILE exec $SERVICE_NAME composer install --no-interaction --optimize-autoloader
} else {
    Write-Host "✅ Folder vendor sudah ada."
}

# Set permission (bisa diabaikan di Windows)
Write-Host "🔧 Menyetel permission folder storage dan cache..."
try {
    docker compose -f $COMPOSE_FILE exec $SERVICE_NAME sh -c "chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"
} catch {
    Write-Host "(Permission diabaikan di Windows)"
}

# Generate key Civika
Write-Host "🔐 Mengenerate key aplikasi Civika..."
docker compose -f $COMPOSE_FILE exec $SERVICE_NAME php artisan key:generate

# Migrasi database
Write-Host "🛠️ Menjalankan migrasi database..."
docker compose -f $COMPOSE_FILE exec $SERVICE_NAME php artisan migrate

# Jalankan Civika server di background
Write-Host "🚀 Menjalankan Civika server di port 8000..."
docker compose -f $COMPOSE_FILE exec -d $SERVICE_NAME php artisan serve --host=0.0.0.0 --port=8000

Write-Host "✅ Setup selesai! Civika tersedia di http://localhost:8000"

npm install
npm run dev
Pause
