<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Fiscal {{ $invoice->number }}</title>
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
        <h1>NOTA FISCAL</h1>
        <h2>{{ $invoice->number }}</h2>
    </div>
    <div class="info">
        <table>
            <tr><td><strong>Cliente:</strong></td><td>{{ optional($invoice->client)->name ?? '-' }}</td></tr>
            <tr><td><strong>Obra:</strong></td><td>{{ optional($invoice->project)->name ?? '-' }}</td></tr>
            <tr><td><strong>Data de Emissão:</strong></td><td>{{ $invoice->issue_date->format('d/m/Y') }}</td></tr>
            <tr><td><strong>Data de Vencimento:</strong></td><td>{{ $invoice->due_date->format('d/m/Y') }}</td></tr>
            <tr><td><strong>Status:</strong></td><td>{{ $invoice->status_label }}</td></tr>
        </table>
    </div>
    <div class="total">
        <p>Subtotal: R$ {{ number_format($invoice->subtotal, 2, ',', '.') }}</p>
        <p>Impostos: R$ {{ number_format($invoice->tax_amount, 2, ',', '.') }}</p>
        <p>Total: R$ {{ number_format($invoice->total_amount, 2, ',', '.') }}</p>
    </div>
    @if($invoice->notes)
    <div class="info">
        <p><strong>Observações:</strong></p>
        <p>{{ $invoice->notes }}</p>
    </div>
    @endif
</body>
</html>

