@echo off
setlocal

echo 🚀 Menjalankan setup Civika secara lokal...

:: Cek .env
if not exist ".env" (
    echo 📄 Menyalin .env.example ke .env...
    copy .env.example .env
)

:: Install Composer
echo 📦 Menginstall dependensi Composer...
composer install --no-interaction --optimize-autoloader

:: Generate key
echo 🔐 Menggenerate app key...
php artisan key:generate

:: Migrasi database
echo 🛠️ Menjalankan migrasi database...
php artisan migrate

php artisan serve

npm install
npm run dev
echo "✅ Setup selesai"

endlocal
pause
