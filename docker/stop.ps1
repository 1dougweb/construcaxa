# Script PowerShell para parar ambiente

Write-Host "🛑 Parando ambiente..." -ForegroundColor Yellow

docker-compose -f docker-compose.prod.yml down

Write-Host "✅ Ambiente parado!" -ForegroundColor Green

