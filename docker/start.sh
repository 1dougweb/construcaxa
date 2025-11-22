#!/bin/bash
# Script para iniciar ambiente de produção

set -e

echo "🚀 Iniciando ambiente de produção..."

# Verificar se o .env existe
if [ ! -f .env ]; then
    echo "❌ Arquivo .env não encontrado. Crie um arquivo .env antes de iniciar."
    exit 1
fi

# Iniciar containers
echo "🐳 Iniciando containers..."
docker-compose -f docker-compose.prod.yml up -d

# Aguardar MySQL estar pronto
echo "⏳ Aguardando MySQL..."
sleep 10

# Executar migrations
echo "📊 Executando migrations..."
docker-compose -f docker-compose.prod.yml exec php php artisan migrate --force || true

# Limpar e cachear configurações
echo "⚡ Otimizando Laravel..."
docker-compose -f docker-compose.prod.yml exec php php artisan config:cache
docker-compose -f docker-compose.prod.yml exec php php artisan route:cache
docker-compose -f docker-compose.prod.yml exec php php artisan view:cache

echo "✅ Ambiente de produção iniciado!"
echo "📝 Acesse: http://localhost:${APP_PORT:-80}"


