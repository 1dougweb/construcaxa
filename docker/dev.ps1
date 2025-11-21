# Script PowerShell para desenvolvimento

Write-Host "🔧 Iniciando ambiente de desenvolvimento..." -ForegroundColor Cyan

# Verificar se o .env existe
if (-not (Test-Path .env)) {
    Write-Host "⚠️  Arquivo .env não encontrado. Copiando .env.example..." -ForegroundColor Yellow
    if (Test-Path .env.example) {
        Copy-Item .env.example .env
    } else {
        Write-Host "⚠️  .env.example não encontrado. Crie um arquivo .env manualmente." -ForegroundColor Yellow
    }
}

# Iniciar containers
Write-Host "🐳 Iniciando containers..." -ForegroundColor Yellow
docker-compose -f docker-compose.prod.yml up -d mysql

# Aguardar MySQL estar pronto
Write-Host "⏳ Aguardando MySQL..." -ForegroundColor Yellow
Start-Sleep -Seconds 10

# Executar migrations
Write-Host "📊 Executando migrations..." -ForegroundColor Yellow
docker-compose -f docker-compose.prod.yml run --rm php php artisan migrate --force

# Iniciar todos os serviços
Write-Host "🚀 Iniciando todos os serviços..." -ForegroundColor Yellow
docker-compose -f docker-compose.prod.yml up -d

# Executar npm run dev em background
Write-Host "⚡ Iniciando Vite dev server..." -ForegroundColor Yellow
$env:APP_PORT = if ($env:APP_PORT) { $env:APP_PORT } else { "80" }
Write-Host "📝 Acesse: http://localhost:$env:APP_PORT" -ForegroundColor Green

Write-Host "✅ Ambiente de desenvolvimento iniciado!" -ForegroundColor Green
Write-Host "💡 Para executar npm run dev, use: docker-compose -f docker-compose.prod.yml exec php npm run dev" -ForegroundColor Cyan

