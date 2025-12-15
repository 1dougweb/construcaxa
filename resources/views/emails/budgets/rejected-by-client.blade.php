<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamento Rejeitado pelo Cliente</title>
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
            background-color: #ef4444;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #ffffff;
            padding: 24px;
            border: 1px solid #e5e7eb;
        }
        .info-box {
            background-color: #fef2f2;
            padding: 16px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #ef4444;
        }
        .rejection-reason {
            background-color: #fff7ed;
            padding: 16px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #f59e0b;
        }
        .footer {
            text-align: center;
            padding: 16px;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Orçamento Rejeitado pelo Cliente</h1>
    </div>
    
    <div class="content">
        <p>Olá,</p>
        
        <p>O cliente <strong>{{ $budget->client?->name ?? 'Cliente' }}</strong> rejeitou o orçamento <strong>#{{ $budget->id }} - v{{ $budget->version }}</strong>.</p>

        <div class="info-box">
            <h3 style="color: #111827; font-size: 18px; margin-bottom: 15px;">Detalhes do Orçamento</h3>
            <p><strong>ID do Orçamento:</strong> #{{ $budget->id }}</p>
            <p><strong>Versão:</strong> {{ $budget->version }}</p>
            <p><strong>Cliente:</strong> {{ $budget->client?->name }}</p>
            <p><strong>Total:</strong> R$ {{ number_format($budget->total, 2, ',', '.') }}</p>
            <p><strong>Rejeitado em:</strong> {{ $budget->rejected_at->format('d/m/Y H:i') }}</p>
        </div>

        @if($budget->rejection_reason)
        <div class="rejection-reason">
            <h3 style="color: #111827; font-size: 18px; margin-bottom: 15px;">Motivo da Rejeição / Contestação</h3>
            <p style="white-space: pre-wrap;">{{ $budget->rejection_reason }}</p>
        </div>
        @endif

        <p style="text-align: center; margin-top: 30px;">
            <a href="{{ route('budgets.index', ['budget_id' => $budget->id]) }}" style="display: inline-block; padding: 12px 24px; background-color: #ef4444; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">
                Ver Orçamento no Sistema
            </a>
        </p>
    </div>
    
    <div class="footer">
        <p>Este é um email automático, por favor não responda diretamente.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
    </div>
</body>
</html>

