#!/bin/bash
# Script para build de produção

set -e

echo "🚀 Iniciando build de produção..."

# Build dos assets Node
echo "📦 Buildando assets Node..."
docker run --rm -v "$(pwd):/app" -w /app node:20-alpine sh -c "npm ci && npm run build"

# Build das imagens Docker
echo "🐳 Buildando imagens Docker..."
docker-compose -f docker-compose.prod.yml build

echo "✅ Build concluído com sucesso!"

