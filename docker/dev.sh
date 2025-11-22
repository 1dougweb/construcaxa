#!/bin/bash
# Script para desenvolvimento

set -e

echo "🔧 Iniciando ambiente de desenvolvimento..."

# Verificar se o .env existe
if [ ! -f .env ]; then
    echo "⚠️  Arquivo .env não encontrado. Copiando .env.example..."
    cp .env.example .env 2>/dev/null || echo "⚠️  .env.example não encontrado. Crie um arquivo .env manualmente."
fi

# Iniciar containers
echo "🐳 Iniciando containers..."
docker-compose -f docker-compose.prod.yml up -d mysql

# Aguardar MySQL estar pronto
echo "⏳ Aguardando MySQL..."
sleep 10

# Executar migrations
echo "📊 Executando migrations..."
docker-compose -f docker-compose.prod.yml run --rm php php artisan migrate --force || true

# Iniciar todos os serviços
echo "🚀 Iniciando todos os serviços..."
docker-compose -f docker-compose.prod.yml up -d

# Executar npm run dev em background
echo "⚡ Iniciando Vite dev server..."
docker-compose -f docker-compose.prod.yml exec -d php sh -c "npm run dev" || echo "⚠️  npm run dev precisa ser executado manualmente"

echo "✅ Ambiente de desenvolvimento iniciado!"
echo "📝 Acesse: http://localhost:${APP_PORT:-80}"


