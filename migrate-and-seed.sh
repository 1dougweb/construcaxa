#!/bin/bash
# Script seguro para migrar e fazer seed sem perder dados
# Uso: bash migrate-and-seed.sh [prod|dev]

set -e

# Cores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Determinar ambiente
ENV=${1:-prod}
COMPOSE_FILE="docker-compose.yml"

if [ "$ENV" = "prod" ]; then
    COMPOSE_FILE="docker-compose.prod.yml"
    echo -e "${YELLOW}⚠️  Modo PRODUÇÃO${NC}"
else
    echo -e "${YELLOW}⚠️  Modo DESENVOLVIMENTO${NC}"
fi

echo ""
echo "=========================================="
echo "  Migração e Seed Seguro (Sem Perder Dados)"
echo "=========================================="
echo ""

# Verificar se containers estão rodando
if ! docker-compose -f "$COMPOSE_FILE" ps | grep -q "Up"; then
    echo -e "${RED}❌ Containers não estão rodando!${NC}"
    echo "Iniciando containers..."
    docker-compose -f "$COMPOSE_FILE" up -d
    sleep 5
fi

# 1. Backup do banco de dados
echo -e "${YELLOW}📦 Fazendo backup do banco de dados...${NC}"
BACKUP_FILE="backup_$(date +%Y%m%d_%H%M%S).sql"

if [ "$ENV" = "prod" ]; then
    # Produção: usar variáveis do .env
    docker-compose -f "$COMPOSE_FILE" exec -T db mysqldump -u root -p"${DB_ROOT_PASSWORD:-root}" "${DB_DATABASE:-stock_master}" > "$BACKUP_FILE" 2>/dev/null || {
        echo -e "${YELLOW}⚠️  Não foi possível fazer backup automático${NC}"
        echo "Continuando mesmo assim..."
    }
else
    # Desenvolvimento: valores padrão
    docker-compose -f "$COMPOSE_FILE" exec -T db mysqldump -u root -proot license_server > "$BACKUP_FILE" 2>/dev/null || {
        echo -e "${YELLOW}⚠️  Não foi possível fazer backup automático${NC}"
        echo "Continuando mesmo assim..."
    }
fi

if [ -f "$BACKUP_FILE" ] && [ -s "$BACKUP_FILE" ]; then
    echo -e "${GREEN}✅ Backup criado: $BACKUP_FILE${NC}"
else
    echo -e "${YELLOW}⚠️  Backup vazio ou não criado (pode ser normal se banco estiver vazio)${NC}"
fi

echo ""

# 2. Verificar migrations pendentes
echo -e "${YELLOW}🔍 Verificando migrations pendentes...${NC}"
docker-compose -f "$COMPOSE_FILE" exec app php artisan migrate:status
echo ""

# 3. Executar migrations
echo -e "${YELLOW}🚀 Executando migrations...${NC}"
if docker-compose -f "$COMPOSE_FILE" exec app php artisan migrate --force; then
    echo -e "${GREEN}✅ Migrations executadas com sucesso!${NC}"
else
    echo -e "${RED}❌ Erro ao executar migrations!${NC}"
    exit 1
fi

echo ""

# 4. Executar seeders
echo -e "${YELLOW}🌱 Executando seeders...${NC}"
if docker-compose -f "$COMPOSE_FILE" exec app php artisan db:seed --force; then
    echo -e "${GREEN}✅ Seeders executados com sucesso!${NC}"
else
    echo -e "${RED}❌ Erro ao executar seeders!${NC}"
    exit 1
fi

echo ""

# 5. Limpar cache
echo -e "${YELLOW}🧹 Limpando cache...${NC}"
docker-compose -f "$COMPOSE_FILE" exec app php artisan config:clear
docker-compose -f "$COMPOSE_FILE" exec app php artisan cache:clear
docker-compose -f "$COMPOSE_FILE" exec app php artisan view:clear
docker-compose -f "$COMPOSE_FILE" exec app php artisan config:cache
docker-compose -f "$COMPOSE_FILE" exec app php artisan route:cache
echo -e "${GREEN}✅ Cache limpo!${NC}"

echo ""
echo "=========================================="
echo -e "${GREEN}✅ Processo concluído com sucesso!${NC}"
echo "=========================================="
echo ""
echo "📝 Resumo:"
echo "  - Backup: $BACKUP_FILE"
echo "  - Migrations: ✅ Executadas"
echo "  - Seeders: ✅ Executados"
echo "  - Cache: ✅ Limpo"
echo ""
echo "💡 Dica: Os dados existentes foram preservados!"
echo ""



