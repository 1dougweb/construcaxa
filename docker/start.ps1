# Script PowerShell para iniciar ambiente de produção

Write-Host "🚀 Iniciando ambiente de produção..." -ForegroundColor Cyan

# Verificar se o .env existe
if (-not (Test-Path .env)) {
    Write-Host "❌ Arquivo .env não encontrado. Crie um arquivo .env antes de iniciar." -ForegroundColor Red
    exit 1
}

# Iniciar containers
Write-Host "🐳 Iniciando containers..." -ForegroundColor Yellow
docker-compose -f docker-compose.prod.yml up -d

# Aguardar MySQL estar pronto
Write-Host "⏳ Aguardando MySQL..." -ForegroundColor Yellow
Start-Sleep -Seconds 10

# Executar migrations
Write-Host "📊 Executando migrations..." -ForegroundColor Yellow
docker-compose -f docker-compose.prod.yml exec php php artisan migrate --force

# Limpar e cachear configurações
Write-Host "⚡ Otimizando Laravel..." -ForegroundColor Yellow
docker-compose -f docker-compose.prod.yml exec php php artisan config:cache
docker-compose -f docker-compose.prod.yml exec php php artisan route:cache
docker-compose -f docker-compose.prod.yml exec php php artisan view:cache

$env:APP_PORT = if ($env:APP_PORT) { $env:APP_PORT } else { "80" }
Write-Host "✅ Ambiente de produção iniciado!" -ForegroundColor Green
Write-Host "📝 Acesse: http://localhost:$env:APP_PORT" -ForegroundColor Green


