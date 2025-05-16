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

:: Install dependency Civika jika folder vendor belum ada
if not exist "vendor" (
    echo 📦 Menjalankan composer install...
    docker compose -f %COMPOSE_FILE% exec %SERVICE_NAME% composer install --no-interaction --optimize-autoloader
) else (
    echo ✅ Folder vendor sudah ada.
)

:: Set permission (tidak fatal di Windows)
echo 🔧 Menyetel permission folder storage dan cache...
docker compose -f %COMPOSE_FILE% exec %SERVICE_NAME% sh -c "chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache" || echo (Permission diabaikan di Windows)

:: Generate Civika key
echo 🔐 Menggenerate key aplikasi Civika...
docker compose -f %COMPOSE_FILE% exec %SERVICE_NAME% php artisan key:generate

:: Migrasi database
echo 🛠️ Menjalankan migrasi database...
docker compose -f %COMPOSE_FILE% exec %SERVICE_NAME% php artisan migrate

:: Jalankan Civika server di background (opsional)
echo 🚀 Menjalankan Civika server di port 8000...
docker compose -f %COMPOSE_FILE% exec -d %SERVICE_NAME% php artisan serve --host=0.0.0.0 --port=8000

echo ✅ Setup selesai! Civika tersedia di http://localhost:8000

endlocal
pause
