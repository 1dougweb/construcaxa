# Script PowerShell para executar npm run build

Write-Host "📦 Executando npm run build..." -ForegroundColor Cyan

# Verificar se o container está rodando
$containerRunning = docker ps --filter "name=stock-master-php" --format "{{.Names}}"
if (-not $containerRunning) {
    Write-Host "⚠️  Container PHP não está rodando. Iniciando..." -ForegroundColor Yellow
    docker-compose -f docker-compose.prod.yml up -d php
    Start-Sleep -Seconds 5
}

docker-compose -f docker-compose.prod.yml exec php npm run build

Write-Host "✅ Build concluído!" -ForegroundColor Green

