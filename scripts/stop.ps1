$PSScriptRoot = Split-Path -Parent $MyInvocation.MyCommand.Definition
Set-Location $PSScriptRoot
Write-Host "🛑 Menghentikan semua container..."
docker compose -f ../compose.dev.yaml stop