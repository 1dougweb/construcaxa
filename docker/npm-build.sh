#!/bin/bash
# Script para executar npm run build

echo "📦 Executando npm run build..."

docker-compose -f docker-compose.prod.yml exec php npm run build

echo "✅ Build concluído!"

