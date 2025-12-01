# 🚀 Quick Start - Stock Master Docker

## ⚡ Início Rápido

### 1. Configurar Ambiente

```powershell
# Copiar arquivo de exemplo
Copy-Item .env.production.example .env

# Editar .env e configurar:
# - APP_KEY (gerar com: php artisan key:generate)
# - DB_DATABASE, DB_USERNAME, DB_PASSWORD
```

### 2. Build e Iniciar

```powershell
# Build dos assets e imagens
.\docker\build.ps1

# Iniciar ambiente de produção
.\docker\start.ps1
```

### 3. Executar npm run build

```powershell
.\docker\npm-build.ps1
```

### 4. Executar npm run dev

```powershell
.\docker\npm-dev.ps1
```

## 📝 Comandos Principais

| Comando | Descrição |
|---------|-----------|
| `.\docker\build.ps1` | Build dos assets e imagens Docker |
| `.\docker\start.ps1` | Iniciar ambiente de produção |
| `.\docker\dev.ps1` | Iniciar ambiente de desenvolvimento |
| `.\docker\stop.ps1` | Parar todos os containers |
| `.\docker\npm-build.ps1` | Executar `npm run build` |
| `.\docker\npm-dev.ps1` | Executar `npm run dev` |

## 🌐 Acessos

- **Aplicação**: http://localhost:80
- **MySQL**: localhost:3306
- **PHPMyAdmin** (se configurado): http://localhost:8080

## 🔧 Comandos Artisan

```powershell
# Executar migrations
docker-compose -f docker-compose.prod.yml exec php php artisan migrate

# Limpar cache
docker-compose -f docker-compose.prod.yml exec php php artisan cache:clear

# Ver logs
docker-compose -f docker-compose.prod.yml logs -f
```

## 📚 Documentação Completa

Veja [DOCKER.md](./DOCKER.md) para documentação completa.











