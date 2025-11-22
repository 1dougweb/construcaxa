#!/bin/bash
# Script para executar npm run dev

echo "⚡ Executando npm run dev..."
echo "💡 Pressione Ctrl+C para parar"

docker-compose -f docker-compose.prod.yml exec php npm run dev


