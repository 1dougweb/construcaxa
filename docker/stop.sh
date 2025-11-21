#!/bin/bash
# Script para parar ambiente

set -e

echo "🛑 Parando ambiente..."

docker-compose -f docker-compose.prod.yml down

echo "✅ Ambiente parado!"

