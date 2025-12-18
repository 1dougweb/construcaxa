# Configuração do Laravel Reverb

## Problema: WebSocket não está funcionando

Se você não vê logs no console do navegador, o servidor Reverb provavelmente não está rodando.

## Solução

### 1. Verificar se as variáveis de ambiente estão configuradas

No arquivo `.env`, você precisa ter:

```env
REVERB_APP_ID=stock-master
REVERB_APP_KEY=seu-app-key-aqui
REVERB_APP_SECRET=seu-app-secret-aqui
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http
BROADCAST_DRIVER=reverb
```

### 2. Gerar as chaves do Reverb (se ainda não tiver)

```bash
php artisan reverb:generate-keys
```

Isso vai gerar as chaves e adicionar ao `.env` automaticamente.

### 3. Iniciar o servidor Reverb

Em um terminal separado, execute:

```bash
php artisan reverb:start
```

O servidor deve mostrar algo como:
```
Starting Reverb server on 127.0.0.1:8080...
```

### 4. Verificar no navegador

Abra o console do navegador (F12) e você deve ver:
- `[ws-notifications] Echo disponível, iniciando inscrição.`
- `[ws-notifications] ✅ WebSocket conectado`
- `[ws-notifications] ✅ Subscribed to products channel`

### 5. Testar

Crie ou edite um produto e você deve ver:
- `[ws-notifications] ProductChanged event received:`
- `handleProductChanged called with:`
- Toast de notificação aparecendo
- Tabela atualizando automaticamente

## Para desenvolvimento

Você pode usar o Supervisor ou PM2 para manter o servidor rodando, ou simplesmente deixar o terminal aberto durante o desenvolvimento.

## Para produção

Configure um process manager como Supervisor para manter o Reverb rodando automaticamente.