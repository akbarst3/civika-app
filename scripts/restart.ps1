$PSScriptRoot = Split-Path -Parent $MyInvocation.MyCommand.Definition
Set-Location $PSScriptRoot

Write-Host "Menjalankan stop.ps1..."
& "$PSScriptRoot\stop.ps1"

Write-Host "Menjalankan up.ps1..."
& "$PSScriptRoot\up.ps1"