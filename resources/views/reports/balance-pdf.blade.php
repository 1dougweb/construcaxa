<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Balanço de Estoque</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            font-weight: bold;
        }
        .header p {
            font-size: 11px;
            color: #666;
            margin: 3px 0;
        }
        .summary {
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }
        .summary-box {
            display: table-cell;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: top;
        }
        .summary-box h3 {
            font-size: 10px;
            margin: 0 0 5px 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        .summary-box.entradas {
            background-color: #dcfce7;
        }
        .summary-box.saidas {
            background-color: #fee2e2;
        }
        .summary-box.saldo {
            background-color: #dbeafe;
        }
        .summary-box .value {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }
        .summary-box .subvalue {
            font-size: 9px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 9px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        .type {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .type-entrada {
            background-color: #dcfce7;
            color: #166534;
        }
        .type-saida {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            text-align: center;
            font-size: 9px;
            color: #666;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Balanço de Estoque</h1>
        <p>Gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
        @if(request('start_date') || request('end_date'))
            <p>
                Período: 
                {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Início' }}
                até
                {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Hoje' }}
            </p>
        @endif
        @if(request('product_id'))
            @php
                $product = \App\Models\Product::find(request('product_id'));
            @endphp
            <p>Produto: {{ $product ? $product->name . ' (SKU: ' . $product->sku . ')' : 'N/A' }}</p>
        @endif
    </div>

    <!-- Resumo -->
    <div class="summary">
        <div class="summary-box entradas">
            <h3>Total de Entradas</h3>
            <div class="value">{{ number_format($summary['total_entradas'], 2, ',', '.') }}</div>
            <div class="subvalue">Valor: R$ {{ number_format($summary['total_valor_entradas'], 2, ',', '.') }}</div>
        </div>
        <div class="summary-box saidas">
            <h3>Total de Saídas</h3>
            <div class="value">{{ number_format($summary['total_saidas'], 2, ',', '.') }}</div>
            <div class="subvalue">Valor: R$ {{ number_format($summary['total_valor_saidas'], 2, ',', '.') }}</div>
        </div>
        <div class="summary-box saldo">
            <h3>Saldo</h3>
            <div class="value">{{ number_format($summary['saldo_quantidade'], 2, ',', '.') }}</div>
            <div class="subvalue">Valor: R$ {{ number_format($summary['saldo_valor'], 2, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Data/Hora</th>
                <th>Produto</th>
                <th>SKU</th>
                <th>Tipo</th>
                <th class="text-right">Quantidade</th>
                <th class="text-right">Valor Unit.</th>
                <th class="text-right">Valor Total</th>
                <th class="text-right">Estoque Ant.</th>
                <th class="text-right">Estoque Atual</th>
                <th>Usuário</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($movements as $movement)
                @php
                    $unitPrice = $movement->product->cost_price ?? $movement->product->price ?? 0;
                    $totalValue = $movement->quantity * $unitPrice;
                @endphp
                <tr>
                    <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $movement->product->name }}</td>
                    <td>{{ $movement->product->sku }}</td>
                    <td>
                        @if($movement->type === 'entrada')
                            <span class="type type-entrada">Entrada</span>
                        @else
                            <span class="type type-saida">Saída</span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($movement->quantity, 2, ',', '.') }} {{ $movement->product->unit_label }}</td>
                    <td class="text-right">R$ {{ number_format($unitPrice, 2, ',', '.') }}</td>
                    <td class="text-right">R$ {{ number_format($totalValue, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($movement->previous_stock, 2, ',', '.') }} {{ $movement->product->unit_label }}</td>
                    <td class="text-right">{{ number_format($movement->new_stock, 2, ',', '.') }} {{ $movement->product->unit_label }}</td>
                    <td>{{ $movement->user->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">Nenhuma movimentação encontrada.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f5f5f5; font-weight: bold;">
                <td colspan="4" class="text-right">TOTAIS:</td>
                <td class="text-right">{{ number_format($summary['total_entradas'] + $summary['total_saidas'], 2, ',', '.') }}</td>
                <td colspan="2" class="text-right">R$ {{ number_format($summary['total_valor_entradas'] + $summary['total_valor_saidas'], 2, ',', '.') }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Este relatório foi gerado automaticamente pelo sistema de gestão de estoque.</p>
    </div>
</body>
</html>
