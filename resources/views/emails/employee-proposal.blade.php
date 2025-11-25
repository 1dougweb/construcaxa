<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Proposta de Trabalho</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
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
            padding: 30px;
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
        .button-accept {
            background-color: #10b981;
        }
        .button-reject {
            background-color: #ef4444;
        }
        .info-box {
            background-color: white;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #4F46E5;
        }
        .footer {
            text-align: center;
            padding: 20px;
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
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .item-row:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Nova Proposta de Trabalho</h1>
    </div>
    
    <div class="content">
        <p>Olá <strong>{{ $proposal->employee->user->name }}</strong>,</p>
        
        <p>Você recebeu uma nova proposta de trabalho. Abaixo estão os detalhes:</p>
        
        <div class="info-box">
            <h3>Informações da Proposta</h3>
            <p><strong>Valor da Hora:</strong> R$ {{ number_format($proposal->hourly_rate, 2, ',', '.') }}</p>
            <p><strong>Tipo de Contrato:</strong> {{ $proposal->contract_type_label }}</p>
            @if($proposal->contract_type === 'fixed_days')
                <p><strong>Dias:</strong> {{ $proposal->days }}</p>
                <p><strong>Data de Início:</strong> {{ $proposal->start_date ? $proposal->start_date->format('d/m/Y') : 'N/A' }}</p>
                <p><strong>Data de Término:</strong> {{ $proposal->end_date ? $proposal->end_date->format('d/m/Y') : 'N/A' }}</p>
            @endif
            @if($proposal->project)
                <p><strong>Obra:</strong> {{ $proposal->project->name }}</p>
            @endif
            @if($proposal->observations)
                <p><strong>Observações:</strong><br>{{ $proposal->observations }}</p>
            @endif
        </div>
        
        @if($proposal->items->count() > 0)
            <div class="item-list">
                <h3>Itens da Proposta</h3>
                @foreach($proposal->items as $item)
                    <div class="item-row">
                        <p><strong>{{ $item->item_type_label }}:</strong> {{ $item->item_name }}</p>
                        <p>Quantidade: {{ number_format($item->quantity, 2, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        @endif
        
        <div class="info-box">
            <p><strong>Valor Total:</strong> R$ {{ number_format($proposal->total_amount, 2, ',', '.') }}</p>
        </div>
        
        <p style="text-align: center; margin-top: 30px;">
            <a href="{{ route('proposals.view', $proposal->token) }}" class="button">Visualizar Proposta Completa</a>
        </p>
        
        <p style="margin-top: 20px; font-size: 14px; color: #6b7280;">
            <strong>Importante:</strong> Esta proposta expira em {{ $proposal->expires_at->format('d/m/Y H:i') }}.
        </p>
    </div>
    
    <div class="footer">
        <p>Este é um email automático, por favor não responda.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
    </div>
</body>
</html>

