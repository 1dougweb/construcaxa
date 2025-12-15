<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Orçamento Disponível</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f3f4f6;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 24px;
            border: 1px solid #e5e7eb;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4F46E5;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
            font-weight: bold;
        }
        .button-primary {
            background-color: #10b981;
        }
        .info-box {
            background-color: white;
            padding: 16px;
            border-radius: 6px;
            margin: 15px 0;
            border: 1px solidrgb(182, 181, 209);
        }
        .footer {
            text-align: center;
            padding: 16px;
            color: #6b7280;
            font-size: 12px;
        }
        .item-list {
            background-color: white;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .item-row {
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        .item-row:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Novo Orçamento Disponível</h1>
    </div>
    
    <div class="content">
        <p>Olá <strong>{{ $budget->client?->name ?? 'cliente' }}</strong>,</p>
        
        <p>Acabamos de gerar um novo orçamento para você em <strong>{{ config('app.name') }}</strong>.</p>

        <div class="info-box">
            <h3>Resumo do Orçamento</h3>
            <p><strong>ID do Orçamento:</strong> #{{ $budget->id }}</p>
            @if($budget->inspection)
                <p><strong>Vistoria Relacionada:</strong> {{ $budget->inspection->number }}</p>
            @endif
            <p><strong>Versão:</strong> {{ $budget->version }}</p>
            <p><strong>Status Atual:</strong> {{ $budget->status_label }}</p>
            <p><strong>Subtotal:</strong> R$ {{ number_format($budget->subtotal, 2, ',', '.') }}</p>
            <p><strong>Desconto:</strong> R$ {{ number_format($budget->discount, 2, ',', '.') }}</p>
            <p><strong>Total:</strong> <strong>R$ {{ number_format($budget->total, 2, ',', '.') }}</strong></p>
        </div>

        @if($budget->notes)
            <div class="info-box">
                <h3>Observações</h3>
                <p>{{ $budget->notes }}</p>
            </div>
        @endif

        @if($budget->items && $budget->items->count() > 0)
            <div class="item-list">
                <h3>Principais Itens</h3>
                @foreach($budget->items->take(5) as $item)
                    <div class="item-row">
                        <strong>{{ ucfirst($item->item_type) }}:</strong> {{ $item->description }}<br>
                        @if($item->quantity)
                            Quantidade: {{ number_format($item->quantity, 2, ',', '.') }} –
                        @endif
                        Valor: R$ {{ number_format($item->total, 2, ',', '.') }}
                    </div>
                @endforeach
                @if($budget->items->count() > 5)
                    <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">
                        (+ {{ $budget->items->count() - 5 }} itens adicionais)
                    </p>
                @endif
            </div>
        @endif

        <p style="text-align: center; margin-top: 24px;">
            <a href="{{ route('client.budgets.show', $budget) }}" class="button button-primary" style="display: inline-block; padding: 12px 24px; background-color: #4F46E5; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">
                Acessar sistema para analisar e responder este orçamento
            </a>
        </p>
        <p style="text-align: center; margin-top: 15px;">
            <a href="{{ route('client.budgets.show', $budget) }}" style="color: #6b7280; text-decoration: underline; font-size: 14px;">
                Ou clique aqui para aprovar ou rejeitar este orçamento
            </a>
        </p>

        <p style="margin-top: 20px; font-size: 14px; color: #6b7280;">
            Caso você tenha dúvidas ou precise de ajustes neste orçamento, basta responder este e-mail ou entrar em contato com nossa equipe.
        </p>
    </div>
    
    <div class="footer">
        <p>Este é um email automático, por favor não responda diretamente.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
    </div>
</body>
</html>


