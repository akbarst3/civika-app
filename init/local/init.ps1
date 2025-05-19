Write-Host "🚀 Menjalankan setup Civika secara lokal..."

# Cek .env
if (-Not (Test-Path ".env")) {
    Write-Host "📄 Menyalin .env.example ke .env..."
    Copy-Item ".env.example" ".env"
}

# Install Composer
Write-Host "📦 Menginstall dependensi Composer..."
composer install --no-interaction --optimize-autoloader

# Generate key
Write-Host "🔐 Menggenerate app key..."
php artisan key:generate

# Migrasi database
Write-Host "🛠️ Menjalankan migrasi database..."
php artisan migrate

php artisan serve

npm install
npm run dev
echo "✅ Setup selesai"

Pause