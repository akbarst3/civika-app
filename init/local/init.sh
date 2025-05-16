#!/bin/bash
set -e

echo "🚀 Menjalankan setup Civika secara lokal..."

# Cek .env
if [ ! -f .env ]; then
  echo "📄 Menyalin .env.example ke .env..."
  cp .env.example .env
fi

# Install Composer dependencies
echo "📦 Menginstall dependensi Composer..."
composer install --no-interaction --optimize-autoloader

# Set permission
echo "🔧 Menyetel permission folder storage dan cache..."
chmod -R 775 storage bootstrap/cache
chown -R "$USER":"$USER" storage bootstrap/cache

# Generate key
echo "🔐 Menggenerate app key..."
php artisan key:generate

# Migrasi database
echo "🛠️ Menjalankan migrasi database..."
php artisan migrate

# Info
echo "✅ Setup selesai! Jalankan: php artisan serve"
