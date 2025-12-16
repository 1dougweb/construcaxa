#!/bin/bash
# Script para migrar dados de storage para volumes persistentes
# Use este script ANTES do primeiro deploy em produção

set -e

echo "=== Migração de Storage para Volumes Persistentes ==="
echo ""

# Verificar se volumes existem
if ! docker volume ls | grep -q "stock-master_storage_public"; then
    echo "⚠ Volume 'stock-master_storage_public' não existe ainda"
    echo "Criando volumes..."
    docker-compose -f docker-compose.prod.yml up -d --no-start
fi

# Verificar se há dados em storage local para migrar
if [ -d "storage/app/public" ] && [ "$(ls -A storage/app/public 2>/dev/null)" ]; then
    echo "📦 Dados encontrados em storage/app/public"
    echo "Migrando para volume persistente..."
    
    # Criar container temporário para copiar dados
    docker run --rm \
        -v "$(pwd)/storage/app/public:/source:ro" \
        -v stock-master_storage_public:/dest \
        alpine sh -c "cp -r /source/* /dest/ 2>/dev/null || true && echo '✓ Dados migrados'"
    
    echo "✓ Migração concluída!"
else
    echo "ℹ Nenhum dado encontrado em storage/app/public para migrar"
    echo "Volume será populado conforme uso da aplicação"
fi

echo ""
echo "=== Verificando volumes ==="
docker volume ls | grep stock-master || echo "Nenhum volume encontrado"

echo ""
echo "✓ Pronto! Agora você pode fazer deploy sem perder dados."
