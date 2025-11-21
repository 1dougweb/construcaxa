# Script PowerShell para build de produção

Write-Host "🚀 Iniciando build de produção..." -ForegroundColor Cyan

# Build dos assets Node
Write-Host "📦 Buildando assets Node..." -ForegroundColor Yellow
docker run --rm -v "${PWD}:/app" -w /app node:20-alpine sh -c "npm ci && npm run build"

# Build das imagens Docker
Write-Host "🐳 Buildando imagens Docker..." -ForegroundColor Yellow
docker-compose -f docker-compose.prod.yml build

Write-Host "✅ Build concluído com sucesso!" -ForegroundColor Green

