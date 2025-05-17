@echo off
setlocal

set SERVICE_NAME=app
set CONTAINER_NAME=civika-app
set COMPOSE_FILE=compose.dev.yaml

echo 🚀 Memulai setup aplikasi Civika dengan Docker...

:: Cek .env
if not exist ".env" (
    echo 📄 Menyalin .env.example ke .env...
    copy .env.example .env >nul
) else (
    echo ✅ .env sudah ada.
)

:: Build dan jalankan container
echo 🐳 Menjalankan docker compose...
docker compose -f %COMPOSE_FILE% up --build -d

:: Tunggu container aktif
echo ⏳ Menunggu container Civika siap...
:wait_for_container
docker inspect -f "status={{.State.Status}}" %CONTAINER_NAME% 2>nul | findstr "running" >nul
if errorlevel 1 (
    timeout /t 2 >nul
    goto wait_for_container
)
echo ✅ Container %CONTAINER_NAME% aktif!

:: Install dependency PHP jika vendor belum ada
if not exist "vendor" (
    echo 📦 Menjalankan composer install...
    docker compose -f %COMPOSE_FILE% exec %SERVICE_NAME% composer install --no-interaction --optimize-autoloader
) else (
    echo ✅ Folder vendor sudah ada.
)

:: Install dependency Node jika node_modules belum ada
if not exist "node_modules" (
    echo 📦 Menjalankan npm install untuk Bootstrap dan Font Awesome...
    docker compose -f %COMPOSE_FILE% exec %SERVICE_NAME% npm install bootstrap@5.3.3 @fortawesome/fontawesome-free@6.6.0 --save-dev
    echo 📦 Menjalankan npm install untuk dependensi lainnya...
    docker compose -f %COMPOSE_FILE% exec %SERVICE_NAME% npm install
) else (
    echo ✅ Folder node_modules sudah ada.
)

:: Pastikan import CSS tersedia
echo 📥 Memastikan import Bootstrap dan Font Awesome di resources/css/app.css...
docker compose -f %COMPOSE_FILE% exec %SERVICE_NAME% sh -c "test -f resources/css/app.css || touch resources/css/app.css"
docker compose -f %COMPOSE_FILE% exec %SERVICE_NAME% sh -c "grep -q 'bootstrap/dist/css/bootstrap.min.css' resources/css/app.css || printf \"@import 'bootstrap/dist/css/bootstrap.min.css';\n@import '@fortawesome/fontawesome-free/css/all.min.css';\n\" >> resources/css/app.css"

:: Pastikan import JS tersedia
echo 📥 Memastikan import Bootstrap di resources/js/app.js...
docker compose -f %COMPOSE_FILE% exec %SERVICE_NAME% sh -c "test -f resources/js/app.js || touch resources/js/app.js"
docker compose -f %COMPOSE_FILE% exec %SERVICE_NAME% sh -c "grep -q \"import 'bootstrap'\" resources/js/app.js || printf \"import 'bootstrap';\n\" >> resources/js/app.js"

:: Set permission (jika diabaikan di Windows, tidak fatal)
echo 🔧 Menyetel permission folder storage dan cache...
docker compose -f %COMPOSE_FILE% exec %SERVICE_NAME% sh -c "chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache" || echo (Permission diabaikan di Windows)

:: Generate Laravel key
echo 🔐 Menggenerate key aplikasi Civika...
docker compose -f %COMPOSE_FILE% exec %SERVICE_NAME% php artisan key:generate

:: Jalankan migrasi
echo 🛠️ Menjalankan migrasi database...
docker compose -f %COMPOSE_FILE% exec %SERVICE_NAME% php artisan migrate

:: Jalankan Laravel server
echo 🚀 Menjalankan Civika server di port 8000...
docker compose -f %COMPOSE_FILE% exec -d %SERVICE_NAME% php artisan serve --host=0.0.0.0 --port=8000

echo ✅ Setup selesai! Civika tersedia di http://localhost:8000
echo ⚠️ Untuk Menjalankan vite, jalankan perintah berikut:
echo 1. npm install
echo 2. npm run dev


endlocal
pause
