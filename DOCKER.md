# 🐳 Guia de Deploy Docker - Stock Master

Este guia explica como configurar e executar o ambiente de produção usando Docker, Nginx, Node.js e MySQL.

## 📋 Pré-requisitos

- Docker Engine 20.10+
- Docker Compose 2.0+
- Node.js 20+ (para build local opcional)

## 🚀 Configuração Inicial

### 1. Configurar Variáveis de Ambiente

Copie o arquivo de exemplo e configure as variáveis:

```bash
cp .env.production.example .env
```

Edite o arquivo `.env` e configure:
- `APP_KEY`: Execute `php artisan key:generate` ou defina manualmente
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: Credenciais do MySQL
- `APP_URL`: URL da aplicação

### 2. Build dos Assets

Execute o build dos assets Node antes de iniciar:

**Windows (PowerShell):**
```powershell
.\docker\build.ps1
```

**Linux/Mac:**
```bash
chmod +x docker/*.sh
./docker/build.sh
```

Ou manualmente:
```bash
npm ci
npm run build
```

## 🏃 Executando o Ambiente

### Desenvolvimento

Para desenvolvimento com hot-reload do Vite:

**Windows (PowerShell):**
```powershell
.\docker\dev.ps1
```

**Linux/Mac:**
```bash
./docker/dev.sh
```

Isso irá:
1. Iniciar o MySQL
2. Executar migrations
3. Iniciar PHP-FPM e Nginx
4. Preparar o ambiente para `npm run dev`

Para executar o Vite dev server:
```bash
docker-compose -f docker-compose.prod.yml exec php npm run dev
```

### Produção

Para ambiente de produção otimizado:

**Windows (PowerShell):**
```powershell
.\docker\start.ps1
```

**Linux/Mac:**
```bash
./docker/start.sh
```

Isso irá:
1. Iniciar todos os serviços
2. Executar migrations
3. Cachear configurações do Laravel
4. Otimizar performance

### Parar o Ambiente

**Windows (PowerShell):**
```powershell
.\docker\stop.ps1
```

**Linux/Mac:**
```bash
./docker/stop.sh
```

## 📦 Estrutura dos Containers

### Serviços

- **nginx**: Servidor web na porta 80
- **php**: PHP-FPM 8.2 com Laravel
- **mysql**: MySQL 8.0 na porta 3306

### Volumes

- `mysql_data`: Dados persistentes do MySQL
- `php-storage`: Storage do Laravel (compartilhado com Nginx)

## 🔧 Comandos Úteis

### Executar Artisan Commands

```bash
docker-compose -f docker-compose.prod.yml exec php php artisan [comando]
```

Exemplos:
```bash
# Migrations
docker-compose -f docker-compose.prod.yml exec php php artisan migrate

# Cache clear
docker-compose -f docker-compose.prod.yml exec php php artisan cache:clear

# Tinker
docker-compose -f docker-compose.prod.yml exec php php artisan tinker
```

### Acessar MySQL

```bash
docker-compose -f docker-compose.prod.yml exec mysql mysql -u stock_user -p stock_master
```

### Ver Logs

```bash
# Todos os serviços
docker-compose -f docker-compose.prod.yml logs -f

# Serviço específico
docker-compose -f docker-compose.prod.yml logs -f php
docker-compose -f docker-compose.prod.yml logs -f nginx
docker-compose -f docker-compose.prod.yml logs -f mysql
```

### Rebuild das Imagens

```bash
docker-compose -f docker-compose.prod.yml build --no-cache
```

## 🛠️ Troubleshooting

### Permissões de Storage

Se houver problemas com permissões:

```bash
docker-compose -f docker-compose.prod.yml exec php chown -R www-data:www-data storage bootstrap/cache
docker-compose -f docker-compose.prod.yml exec php chmod -R 775 storage bootstrap/cache
```

### Limpar Cache do Laravel

```bash
docker-compose -f docker-compose.prod.yml exec php php artisan optimize:clear
```

### Rebuild Completo

```bash
docker-compose -f docker-compose.prod.yml down -v
docker-compose -f docker-compose.prod.yml build --no-cache
docker-compose -f docker-compose.prod.yml up -d
```

## 📝 Notas Importantes

1. **APP_KEY**: Sempre defina uma `APP_KEY` única em produção
2. **DB_PASSWORD**: Use senhas fortes em produção
3. **APP_DEBUG**: Deixe como `false` em produção
4. **Assets**: Sempre execute `npm run build` antes do deploy
5. **Migrations**: Execute migrations após iniciar o MySQL

## 🔒 Segurança

- Não commite o arquivo `.env`
- Use senhas fortes para o banco de dados
- Configure firewall adequadamente
- Use HTTPS em produção (configure certificado SSL no Nginx)

## 📚 Recursos Adicionais

- [Laravel Documentation](https://laravel.com/docs)
- [Docker Documentation](https://docs.docker.com/)
- [Nginx Documentation](https://nginx.org/en/docs/)

