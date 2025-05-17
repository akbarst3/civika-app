@echo off
cd /d "%~dp0"
echo 🛑 Menghentikan semua container...
docker compose -f ../compose.dev.yaml stop