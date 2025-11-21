<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Orçamento #{{ $budget->id }} - v{{ $budget->version }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
        }
        .company-info {
            text-align: center;
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-row {
            margin-bottom: 8px;
            display: flex;
        }
        .label {
            font-weight: bold;
            width: 120px;
            display: inline-block;
        }
        .value {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #333;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-section {
            margin-top: 20px;
            border-top: 2px solid #333;
            padding-top: 15px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }
        .total-label {
            font-weight: bold;
        }
        .total-value {
            font-weight: bold;
        }
        .grand-total {
            font-size: 16px;
            color: #333;
            border-top: 1px solid #333;
            padding-top: 10px;
            margin-top: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-approved { background-color: #d4edda; color: #155724; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-under_review { background-color: #cce7ff; color: #004085; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
        .status-cancelled { background-color: #f5c6cb; color: #721c24; }
        .notes-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Orçamento</div>
        <div class="subtitle">
            Número: #{{ $budget->id }} - Versão {{ $budget->version }}
            <span class="status-badge status-{{ $budget->status }}">{{ $budget->status_label }}</span>
        </div>
    </div>

    <!-- Company Information -->
    <div class="company-info">
        <strong>{{ config('app.name', 'Empresa') }}</strong><br>
        <!-- Add your company details here -->
        Endereço da Empresa<br>
        Telefone: (00) 0000-0000 | Email: contato@empresa.com
    </div>

    <!-- Client Information -->
    <div class="info-section">
        <div class="info-title">Dados do Cliente</div>
        @if($budget->client)
            <div class="info-row">
                <span class="label">Nome:</span>
                <span class="value">{{ $budget->client->name }}</span>
            </div>
            <div class="info-row">
                <span class="label">Email:</span>
                <span class="value">{{ $budget->client->email }}</span>
            </div>
            @if($budget->client->phone)
                <div class="info-row">
                    <span class="label">Telefone:</span>
                    <span class="value">{{ $budget->client->phone }}</span>
                </div>
            @endif
        @else
            <div class="info-row">
                <span class="value">Cliente não especificado</span>
            </div>
        @endif
    </div>

    <!-- Project Information -->
    @if($budget->project)
    <div class="info-section">
        <div class="info-title">Dados do Projeto</div>
        <div class="info-row">
            <span class="label">Projeto:</span>
            <span class="value">{{ $budget->project->name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Código:</span>
            <span class="value">{{ $budget->project->code }}</span>
        </div>
        @if($budget->project->os_number)
            <div class="info-row">
                <span class="label">OS Número:</span>
                <span class="value">{{ $budget->project->os_number }}</span>
            </div>
        @endif
        @if($budget->project->address)
            <div class="info-row">
                <span class="label">Endereço:</span>
                <span class="value">{{ $budget->project->address }}</span>
            </div>
        @endif
    </div>
    @endif

    <!-- Budget Information -->
    <div class="info-section">
        <div class="info-title">Informações do Orçamento</div>
        <div class="info-row">
            <span class="label">Data de Criação:</span>
            <span class="value">{{ $budget->created_at->format('d/m/Y H:i') }}</span>
        </div>
        @if($budget->approved_at)
            <div class="info-row">
                <span class="label">Data de Aprovação:</span>
                <span class="value">{{ $budget->approved_at->format('d/m/Y H:i') }}</span>
            </div>
        @endif
        @if($budget->approver)
            <div class="info-row">
                <span class="label">Aprovado por:</span>
                <span class="value">{{ $budget->approver->name }}</span>
            </div>
        @endif
    </div>

    <!-- Items Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 40%">Descrição</th>
                <th style="width: 15%" class="text-center">Quantidade</th>
                <th style="width: 20%" class="text-right">Valor Unitário</th>
                <th style="width: 25%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($budget->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->description }}</strong>
                        <br>
                        @if($item->item_type === 'product' && $item->product)
                            <small style="color: #666;">Produto: {{ $item->product->name }}</small>
                        @elseif($item->item_type === 'service' && $item->service)
                            <small style="color: #666;">Serviço: {{ $item->service->name }} ({{ $item->service->unit_type_label }})</small>
                        @elseif($item->item_type === 'labor' && $item->laborType)
                            <small style="color: #666;">Mão de Obra: {{ $item->laborType->name }} ({{ $item->laborType->skill_level_label }})</small>
                        @else
                            <small style="color: #666;">{{ $item->item_type_label }}</small>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($item->item_type === 'labor')
                            {{ $item->quantity_display }}
                        @else
                            {{ number_format($item->quantity, 3, ',', '.') }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if($item->item_type === 'labor')
                            {{ $item->laborType ? $item->laborType->formatted_hourly_rate : 'R$ ' . number_format($item->unit_price, 2, ',', '.') . '/h' }}
                        @else
                            R$ {{ number_format($item->unit_price, 2, ',', '.') }}
                        @endif
                    </td>
                    <td class="text-right">R$ {{ number_format($item->total, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals Section -->
    <div class="totals-section">
        <div class="total-row">
            <span class="total-label">Subtotal:</span>
            <span class="total-value">R$ {{ number_format($budget->subtotal, 2, ',', '.') }}</span>
        </div>
        @if($budget->discount > 0)
            <div class="total-row">
                <span class="total-label">Desconto:</span>
                <span class="total-value">-R$ {{ number_format($budget->discount, 2, ',', '.') }}</span>
            </div>
        @endif
        <div class="total-row grand-total">
            <span class="total-label">TOTAL GERAL:</span>
            <span class="total-value">R$ {{ number_format($budget->total, 2, ',', '.') }}</span>
        </div>
    </div>

    <!-- Notes Section -->
    @if($budget->notes)
        <div class="notes-section">
            <div class="info-title">Observações</div>
            <div>{{ $budget->notes }}</div>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Este orçamento foi gerado automaticamente pelo sistema em {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Orçamento válido por 30 dias a partir da data de emissão.</p>
    </div>
</body>
</html>
