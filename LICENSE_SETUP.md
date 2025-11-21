# Sistema de Licenciamento - Documentação

## Visão Geral

O sistema de licenciamento foi implementado para proteger a plataforma contra clonagem não autorizada. Ele valida a licença através de um servidor de licenças externo e bloqueia o acesso às funcionalidades principais do dashboard quando a licença é inválida.

## Componentes Implementados

### 1. Model License (`app/Models/License.php`)
- Armazena informações da licença (token, URL do servidor, device_id, domínio)
- Mantém status de validação e dados da licença
- Gera device_id único baseado em IP, User-Agent e chave da aplicação

### 2. Service LicenseService (`app/Services/LicenseService.php`)
- Valida licença com o servidor de licenças via API
- Implementa cache de validação (1 hora)
- Suporta modo offline temporário (usa cache se servidor indisponível)
- Armazena e atualiza configuração de licença

### 3. Middleware CheckLicense (`app/Http/Middleware/CheckLicense.php`)
- Bloqueia acesso às rotas protegidas se licença inválida
- Permite acesso às rotas de configuração de licença mesmo sem licença válida
- Redireciona para página de configuração se licença inválida

### 4. Controller LicenseController (`app/Http/Controllers/LicenseController.php`)
- `configure()`: Exibe página de configuração de licença
- `store()`: Salva e valida nova licença
- `validateLicense()`: Valida licença manualmente

### 5. View (`resources/views/license/configure.blade.php`)
- Interface para configurar código da licença
- Exibe status da licença (válida/inválida/não configurada)
- Mostra informações técnicas (device_id, domínio, última validação)

## Configuração

### Variáveis de Ambiente

Adicione no arquivo `.env`:

```env
LICENSE_SERVER_URL=https://seu-servidor-de-licenca.com
```

### Migração

A migration já foi executada. Se precisar executar novamente:

```bash
php artisan migrate
```

## Como Usar

### 1. Configurar Licença

1. Acesse o menu "Licença" no sidebar (apenas para admins/gerentes)
2. Ou acesse diretamente: `/license/configure`
3. Cole o código da licença fornecido pelo provedor
4. (Opcional) Informe a URL do servidor de licenças, se diferente do `.env`
5. Clique em "Salvar e Validar"

### 2. Validação Automática

- A licença é validada automaticamente a cada requisição
- O resultado é cacheado por 1 hora para melhor performance
- Se o servidor estiver indisponível, usa cache válido por até 48 horas

### 3. Validação Manual

- Clique em "Validar Licença" na página de configuração
- Útil para forçar uma nova validação sem esperar o cache expirar

## Rotas Protegidas

Todas as rotas principais do dashboard estão protegidas pelo middleware `license`:

- Dashboard
- Produtos
- Categorias
- Pedidos de Materiais
- Fornecedores
- Funcionários
- Relatórios
- Obras/Projetos
- Financeiro
- E todas as outras funcionalidades principais

## Rotas Não Protegidas

As seguintes rotas permanecem acessíveis mesmo sem licença válida:

- `/login` - Página de login
- `/logout` - Logout
- `/license/*` - Todas as rotas de configuração de licença

## API do Servidor de Licenças

O sistema espera que o servidor de licenças tenha o seguinte endpoint:

**POST** `/api/license/validate`

**Request Body:**
```json
{
  "token": "codigo-da-licenca",
  "domain": "dominio-permitido.com",
  "device_id": "id-unico-do-dispositivo"
}
```

**Response (Sucesso):**
```json
{
  "valid": true,
  "message": "Licença válida",
  "license": {
    "id": 1,
    "product_id": 1,
    "expires_at": "2025-12-31T23:59:59Z",
    "device_limit": 5
  }
}
```

**Response (Erro):**
```json
{
  "valid": false,
  "message": "Token inválido"
}
```

## Segurança

- Device ID único gerado por instalação
- Validação de domínio permitido
- Cache inteligente para evitar sobrecarga no servidor
- Modo offline temporário para resiliência
- Logs de validação para auditoria

## Troubleshooting

### Licença não valida

1. Verifique se o código da licença está correto
2. Verifique se a URL do servidor está acessível
3. Verifique se o domínio está permitido na licença
4. Verifique os logs em `storage/logs/laravel.log`

### Erro de conexão com servidor

- O sistema tentará usar cache válido por até 48 horas
- Se não houver cache, será necessário configurar uma licença válida

### Limite de dispositivos atingido

- Cada instalação gera um device_id único
- Se atingir o limite, será necessário desativar dispositivos antigos no servidor de licenças

## Notas Importantes

- A primeira vez que acessar o sistema sem licença, será redirecionado para a página de configuração
- Apenas usuários com role `admin` ou `manager` podem acessar a página de configuração de licença
- A validação é feita de forma assíncrona e não bloqueia o uso do sistema se houver cache válido

