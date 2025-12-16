# Guia de Migração e Seed Seguro (Sem Perder Dados)

## ⚠️ IMPORTANTE: Backup Antes de Qualquer Operação

**SEMPRE faça backup do banco de dados antes de executar migrations:**

```bash
# Backup do banco de dados
docker-compose -f docker-compose.prod.yml exec db mysqldump -u root -p${DB_ROOT_PASSWORD} ${DB_DATABASE} > backup_$(date +%Y%m%d_%H%M%S).sql

# OU se estiver usando docker-compose.yml (desenvolvimento)
docker-compose exec db mysqldump -u root -proot license_server > backup_$(date +%Y%m%d_%H%M%S).sql
```

---

## 🚀 Comandos Seguros para Produção

### 1. Migrations (Adiciona novas tabelas/colunas - NÃO remove dados)

```bash
# Executar apenas migrations pendentes (seguro - não remove dados)
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Verificar status das migrations (sem executar)
docker-compose -f docker-compose.prod.yml exec app php artisan migrate:status
```

**O que faz:**
- ✅ Adiciona novas colunas (nullable) sem perder dados existentes
- ✅ Cria novas tabelas
- ✅ Adiciona índices e foreign keys
- ❌ **NÃO** remove dados ou colunas (a menos que você execute `migrate:rollback`)

### 2. Seeders (Usa firstOrCreate - NÃO duplica dados)

```bash
# Seed completo (seguro - não duplica)
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --force

# Seed específico (exemplo: roles e permissões)
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=RolePermissionSeeder --force

# Seed de dados iniciais (categorias, permissões, etc)
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=InitialDataSeeder --force
```

**O que faz:**
- ✅ Cria apenas se não existir (usa `firstOrCreate`)
- ✅ Não duplica dados existentes
- ✅ Adiciona novos dados que faltam

### 3. Comando Completo (Migrations + Seed)

```bash
# Executar migrations e seed em sequência (SEGURO)
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --force
```

---

## 🔍 Verificações Antes de Executar

### Verificar migrations pendentes:
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan migrate:status
```

### Verificar se há migrations que REMOVEM dados (cuidado!):
```bash
# Listar todas as migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate:status

# Ver conteúdo de uma migration específica (se suspeitar)
docker-compose -f docker-compose.prod.yml exec app cat database/migrations/NOME_DA_MIGRATION.php
```

---

## 📋 Seeders Disponíveis e o que Fazem

### `DatabaseSeeder`
- Chama: `UserSeeder`, `EmployeeSeeder`, `InspectionEnvironmentTemplateSeeder`
- **Seguro**: Usa `firstOrCreate` - não duplica

### `InitialDataSeeder`
- Cria permissões e roles (se não existirem)
- Cria usuário admin padrão (se não existir)
- Cria categorias de produtos (se não existirem)
- **Seguro**: Usa `firstOrCreate` - não duplica

### `RolePermissionSeeder`
- Cria permissões básicas (se não existirem)
- Cria roles (admin, manager, operator)
- **Seguro**: Usa `firstOrCreate` - não duplica

### `UserSeeder`
- Cria usuário admin padrão (admin@admin.com)
- **Seguro**: Usa `firstOrCreate` - não duplica

---

## ⚡ Comando Rápido (Tudo de Uma Vez)

```bash
# Backup + Migrate + Seed (SEGURO)
docker-compose -f docker-compose.prod.yml exec db mysqldump -u root -p${DB_ROOT_PASSWORD} ${DB_DATABASE} > backup_$(date +%Y%m%d_%H%M%S).sql && \
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force && \
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --force && \
echo "✅ Migração e seed concluídos com sucesso!"
```

---

## 🛡️ Garantias de Segurança

### Migrations são seguras porque:
1. **Adicionam colunas como `nullable()`** - não quebram dados existentes
2. **Criam novas tabelas** - não afetam tabelas existentes
3. **Adicionam índices** - melhoram performance sem perder dados
4. **NÃO removem dados** - a menos que você execute `migrate:rollback` explicitamente

### Seeders são seguros porque:
1. **Usam `firstOrCreate`** - criam apenas se não existir
2. **Não duplicam** - verificam antes de criar
3. **Adicionam apenas o que falta** - não sobrescrevem dados existentes

---

## 🚨 CUIDADO: Comandos que PODEM Perder Dados

**NÃO execute estes comandos em produção sem backup:**

```bash
# ❌ NÃO FAÇA (remove todas as tabelas e recria)
php artisan migrate:fresh --seed

# ❌ NÃO FAÇA (remove todas as tabelas)
php artisan migrate:fresh

# ❌ NÃO FAÇA (reverte migrations - pode perder dados)
php artisan migrate:rollback

# ❌ NÃO FAÇA (reverte todas as migrations)
php artisan migrate:reset
```

---

## 📝 Exemplo de Uso em Produção

```bash
# 1. Backup
docker-compose -f docker-compose.prod.yml exec db mysqldump -u root -p${DB_ROOT_PASSWORD} ${DB_DATABASE} > backup.sql

# 2. Verificar migrations pendentes
docker-compose -f docker-compose.prod.yml exec app php artisan migrate:status

# 3. Executar migrations (seguro)
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# 4. Executar seeders (seguro - não duplica)
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --force

# 5. Limpar cache
docker-compose -f docker-compose.prod.yml exec app php artisan config:clear
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
```

---

## 🔄 Para Desenvolvimento (docker-compose.yml)

```bash
# Mesmos comandos, mas sem o -f docker-compose.prod.yml
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

---

## ✅ Checklist Antes de Executar

- [ ] Backup do banco de dados feito
- [ ] Verificado migrations pendentes (`migrate:status`)
- [ ] Confirmado que migrations não removem dados
- [ ] Ambiente de produção identificado corretamente
- [ ] Cache limpo após migrations

---

## 🆘 Em Caso de Problema

Se algo der errado:

```bash
# 1. Parar containers
docker-compose -f docker-compose.prod.yml down

# 2. Restaurar backup
docker-compose -f docker-compose.prod.yml up -d db
docker-compose -f docker-compose.prod.yml exec db mysql -u root -p${DB_ROOT_PASSWORD} ${DB_DATABASE} < backup.sql

# 3. Reiniciar aplicação
docker-compose -f docker-compose.prod.yml up -d
```



