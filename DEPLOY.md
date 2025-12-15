# Guia de Deploy em Produção

## Otimizações Implementadas

### 1. Cache do Docker
- ✅ Dependências (Composer e NPM) são instaladas ANTES de copiar o código
- ✅ Cache mounts para `/root/.composer/cache` e `/root/.npm`
- ✅ Cache mounts para `vendor` e `node_modules` (evita reinstalar se não mudou)
- ✅ Build usa `--prefer-dist` e `--prefer-offline` para acelerar

### 2. Persistência de Fotos e Arquivos
- ✅ Volumes nomeados para `storage/app/public` (fotos de produtos)
- ✅ Volumes nomeados para `storage/framework` (cache, sessões, views)
- ✅ Volumes nomeados para `storage/logs` (logs da aplicação)
- ✅ Dados persistem entre rebuilds do container

## Como Fazer Deploy

### ⚠️ IMPORTANTE: Migração de Dados (Primeira vez ou se perdeu dados)

Se você já tem fotos/arquivos em produção e vai usar volumes persistentes pela primeira vez:

```bash
# 1. Executar script de migração (copia dados locais para volumes)
bash docker-migrate-storage.sh

# OU manualmente:
# Criar volumes primeiro
docker-compose -f docker-compose.prod.yml up -d --no-start

# Copiar dados existentes para volume (se houver)
docker run --rm \
  -v "$(pwd)/storage/app/public:/source:ro" \
  -v stock-master_storage_public:/dest \
  alpine sh -c "cp -r /source/* /dest/ 2>/dev/null || true"
```

### Primeira vez (setup inicial):

```bash
# 1. Copiar arquivo de ambiente
cp .env.example .env

# 2. Configurar variáveis de ambiente no .env
# (DB_DATABASE, DB_USERNAME, DB_PASSWORD, etc.)

# 3. Build da imagem (primeira vez pode demorar)
docker-compose -f docker-compose.prod.yml build

# 4. Iniciar containers
docker-compose -f docker-compose.prod.yml up -d

# 5. Executar migrations e configurações iniciais
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker-compose -f docker-compose.prod.yml exec app php artisan storage:link
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
```

### Deploy de atualizações (código mudou):

```bash
# 1. Rebuild apenas se necessário (Docker usa cache automaticamente)
# Se mudou apenas código, use cache (muito mais rápido):
docker-compose -f docker-compose.prod.yml build app

# Se mudou dependências (composer.json, package.json), force rebuild:
docker-compose -f docker-compose.prod.yml build --no-cache app

# 2. Recriar container (volumes persistem automaticamente - FOTOS SÃO PRESERVADAS)
docker-compose -f docker-compose.prod.yml up -d --force-recreate app

# 3. Executar migrations se necessário
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# 4. Limpar cache se necessário
docker-compose -f docker-compose.prod.yml exec app php artisan config:clear
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
docker-compose -f docker-compose.prod.yml exec app php artisan view:clear
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
```

### ⚠️ PRESERVAÇÃO DE FOTOS E ARQUIVOS

**IMPORTANTE**: Com `docker-compose.prod.yml`, as fotos e arquivos são preservados automaticamente entre rebuilds porque estão em volumes persistentes:

- `storage_public` → contém todas as fotos de produtos
- `storage_framework` → cache, sessões, views
- `storage_logs` → logs da aplicação

**NUNCA** faça:
```bash
# ❌ NÃO FAÇA ISSO (remove volumes e perde dados):
docker-compose -f docker-compose.prod.yml down -v
```

**Para verificar se dados estão preservados:**
```bash
# Ver conteúdo do volume de fotos
docker run --rm -v stock-master_storage_public:/data alpine ls -la /data/products/photos

# Ver tamanho do volume
docker volume inspect stock-master_storage_public
```

### Verificar status:

```bash
# Ver logs
docker-compose -f docker-compose.prod.yml logs -f app

# Ver status dos containers
docker-compose -f docker-compose.prod.yml ps

# Ver volumes (dados persistentes)
docker volume ls | grep stock-master
```

## Importante

1. **Fotos e arquivos**: Estão em volumes persistentes, não serão perdidos em rebuilds
2. **Cache do Docker**: Rebuilds são rápidos se apenas código mudou (dependências usam cache)
3. **Produção**: Use `docker-compose.prod.yml` (sem bind mount do código)
4. **Desenvolvimento**: Use `docker-compose.yml` (com bind mount para hot reload)

## Troubleshooting

### Fotos não aparecem:
```bash
# Verificar se o symlink existe
docker-compose -f docker-compose.prod.yml exec app ls -la /var/www/public/storage

# Recriar symlink se necessário
docker-compose -f docker-compose.prod.yml exec app php artisan storage:link

# Verificar permissões
docker-compose -f docker-compose.prod.yml exec app chmod -R 755 /var/www/storage/app/public
docker-compose -f docker-compose.prod.yml exec app chown -R www:www /var/www/storage
```

### Rebuild muito lento:
- Verifique se está usando `docker-compose.prod.yml` (sem bind mount)
- Cache do Docker deve acelerar se dependências não mudaram
- Primeira build sempre demora mais

### Dados perdidos:
- Verifique se os volumes estão montados: `docker volume ls`
- Verifique se volumes estão sendo usados: `docker-compose -f docker-compose.prod.yml config`
