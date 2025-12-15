<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vistoria concluída</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #111827;
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
            background-color: #ffffff;
            padding: 24px;
            border: 1px solid #e5e7eb;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4F46E5;
            color: white;
            text-decoration: none;
            border-radius: 9999px;
            margin: 10px 5px;
            font-weight: bold;
            font-size: 14px;
        }
        .button-secondary {
            background-color: #6b7280;
        }
        .info-box {
            background-color: #f9fafb;
            padding: 16px;
            border-radius: 6px;
            margin: 16px 0;
            border-left: 4px solid #4F46E5;
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
        <h1>Vistoria concluída</h1>
        <p>Vistoria #{{ $inspection->number }}</p>
    </div>

    <div class="content">
        <p>Olá <strong>{{ $inspection->client->name ?? $inspection->client->trading_name }}</strong>,</p>

        <p>
            Acabamos de concluir a vistoria referente ao seu imóvel/obra.
            Abaixo você encontra um resumo e um link para acessar todos os detalhes.
        </p>

        <div class="info-box">
            <p><strong>Número da vistoria:</strong> {{ $inspection->number }}</p>
            <p><strong>Data:</strong> {{ optional($inspection->inspection_date)->format('d/m/Y') }}</p>
            @if($inspection->address)
                <p><strong>Endereço:</strong> {{ $inspection->address }}</p>
            @endif
        </div>

        <p style="text-align: center; margin-top: 24px; color: #ffffff!important;">
            <a href="{{ $inspection->public_url }}" class="button">
                Verificar vistoria
            </a>
        </p>

        <p style="margin-top: 20px; font-size: 14px; color: #4b5563;">
            Ao acessar o link acima, você poderá visualizar todos os ambientes, observações e fotos,
            e então registrar a sua decisão:
        </p>
        <ul style="margin-top: 8px; padding-left: 20px; font-size: 14px; color: #4b5563;">
            <li>Aprovar a vistoria;</li>
            <li>Ou contestar, deixando suas observações.</li>
        </ul>

        <p style="margin-top: 12px; font-size: 14px; color: #4b5563;">
            Essa decisão só poderá ser registrada uma única vez, para sua segurança e transparência do processo.
        </p>
    </div>

    <div class="footer">
        <p>Este é um email automático, por favor não responda.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
    </div>
</body>
</html>


