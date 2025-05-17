$PSScriptRoot = Split-Path -Parent $MyInvocation.MyCommand.Definition
Set-Location $PSScriptRoot
Write-Host "🚀 Menyalakan container Laravel dan service..."
docker compose -f ../compose.dev.yaml up -d
Start-Sleep -Seconds 5
docker compose -f ../compose.dev.yaml exec app php artisan serve --host=0.0.0.0 --port=8000
