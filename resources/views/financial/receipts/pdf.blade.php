<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo {{ $receipt->number }}</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .info { margin-bottom: 20px; }
        .info table { width: 100%; border-collapse: collapse; }
        .info td { padding: 5px; border-bottom: 1px solid #ddd; }
        .total { font-size: 18px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RECIBO</h1>
        <h2>{{ $receipt->number }}</h2>
    </div>
    <div class="info">
        <table>
            <tr><td><strong>Cliente:</strong></td><td>{{ optional($receipt->client)->name ?? '-' }}</td></tr>
            <tr><td><strong>Obra:</strong></td><td>{{ optional($receipt->project)->name ?? '-' }}</td></tr>
            <tr><td><strong>Data:</strong></td><td>{{ $receipt->issue_date->format('d/m/Y') }}</td></tr>
            <tr><td><strong>Forma de Pagamento:</strong></td><td>{{ $receipt->payment_method_label }}</td></tr>
            @if($receipt->description)
            <tr><td><strong>Descrição:</strong></td><td>{{ $receipt->description }}</td></tr>
            @endif
        </table>
    </div>
    <div class="total">
        <p>Valor Recebido: R$ {{ number_format($receipt->amount, 2, ',', '.') }}</p>
    </div>
    @if($receipt->notes)
    <div class="info">
        <p><strong>Observações:</strong></p>
        <p>{{ $receipt->notes }}</p>
    </div>
    @endif
</body>
</html>

