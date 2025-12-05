# Configuração do Reverb em Produção

## Problema
Em produção, a chave `REVERB_APP_KEY` não está sendo encontrada porque as variáveis de ambiente do Vite não estão disponíveis após o build.

## Solução Implementada
As configurações do Reverb agora são passadas do Laravel (backend) para o frontend via `window.Laravel.reverb`, garantindo que funcionem tanto em desenvolvimento quanto em produção.

## Variáveis de Ambiente Necessárias

No arquivo `.env` do servidor de produção, certifique-se de ter:

```env
REVERB_APP_ID=stock-master
REVERB_APP_KEY=sua-chave-aqui
REVERB_APP_SECRET=seu-secret-aqui
REVERB_HOST=seu-dominio.com
REVERB_PORT=443
REVERB_SCHEME=https
```

## Como Gerar as Chaves

Execute no servidor de produção:

```bash
php artisan reverb:install
```

Isso criará as chaves necessárias. Depois, adicione-as ao `.env`.

## Verificação

1. **Verificar se as variáveis estão configuradas:**
   ```bash
   php artisan tinker
   >>> config('reverb.apps.apps.main.key')
   ```

2. **Verificar no navegador:**
   - Abra o console do navegador
   - Digite: `window.Laravel.reverb`
   - Deve mostrar as configurações do Reverb

3. **Se ainda não funcionar:**
   - Limpe o cache de configuração: `php artisan config:clear`
   - Limpe o cache de views: `php artisan view:clear`
   - Recompile os assets: `npm run build`

## Nota Importante

As variáveis `VITE_REVERB_*` só são necessárias em **desenvolvimento**. Em produção, as configurações vêm do Laravel via `window.Laravel.reverb`.
