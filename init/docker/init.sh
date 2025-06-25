#!/bin/bash
set -e

# Ganti ini sesuai nama service di docker-compose
SERVICE_NAME=app
CONTAINER_NAME=civika-app
COMPOSE_FILE=compose.dev.yaml

echo "🚀 Memulai setup aplikasi Laravel dengan Docker..."

# Cek .env
if [ ! -f .env ]; then
  echo "📄 Menyalin .env.example ke .env..."
  cp .env.example .env
else
  echo "✅ .env sudah ada."
fi

# Build & up docker compose
echo "🐳 Menjalankan docker compose..."
docker compose -f $COMPOSE_FILE up --build -d

# Tunggu container Laravel aktif
echo "⏳ Menunggu container Laravel siap..."
while [ "$(docker inspect -f '{{.State.Status}}' $CONTAINER_NAME 2>/dev/null)" != "running" ]; do
  sleep 2
done
echo "✅ Container $CONTAINER_NAME aktif!"

# Cek folder vendor
if [ ! -d vendor ]; then
  echo "📦 Folder vendor belum ada, menjalankan composer install..."
  docker compose -f compose.dev.yaml exec $SERVICE_NAME bash composer install --no-interaction --optimize-autoloader
else
  echo "✅ Folder vendor sudah ada."
fi

# Set permission
echo "🔧 Menyetel permission folder storage dan cache..."
docker compose -f $COMPOSE_FILE exec $SERVICE_NAME sh -c "chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"

# Generate key Laravel
echo "🔐 Menggenerate key aplikasi Laravel..."
docker compose -f $COMPOSE_FILE exec $SERVICE_NAME php artisan key:generate

# Migrasi database
echo "🛠️ Menjalankan migrasi database..."
docker compose -f $COMPOSE_FILE exec $SERVICE_NAME php artisan migrate

# Jalankan Laravel server background (opsional jika tidak pakai nginx/php-fpm)
echo "🚀 Menjalankan Laravel server di port 8000..."
docker compose -f $COMPOSE_FILE exec -d $SERVICE_NAME php artisan serve --host=0.0.0.0 --port=8000

echo "✅ Setup selesai! Laravel tersedia di http://localhost:8000"
