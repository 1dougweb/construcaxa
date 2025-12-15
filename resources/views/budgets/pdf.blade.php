<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <title>Orçamento #{{ $budget->id }} - v{{ $budget->version }}</title>
    <style>
        @charset "UTF-8";
        * {
            font-family: 'DejaVu Sans', 'DejaVu Sans Condensed', sans-serif;
        }
        body {
            font-family: 'DejaVu Sans', 'DejaVu Sans Condensed', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            direction: ltr;
            unicode-bidi: embed;
            margin: 0;
            padding: 20px;
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 4px;
            width: 100%;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-left {
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
        }
        .header-right {
            width: 50%;
            vertical-align: top;
            padding-left: 15px;
        }
        .logo {
            max-width: 180px;
            max-height: 70px;
            margin-bottom: 8px;
        }
        .company-info {
            font-size: 11px;
            line-height: 1.5;
            margin-top: 5px;
        }
        .company-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
            padding-top: 10px;
        }
        .client-info {
            font-size: 11px;
            line-height: 1.6;
        }
        .client-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #1e40af;
        }
        .info {
            margin-bottom: 20px;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
            padding-bottom: 5px;
        }
        .info-row {
            margin-bottom: 5px;
            display: flex;
        }
        .label {
            font-weight: bold;
            width: 140px;
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
            padding: 8px;
            text-align: left;
            font-size: 11px;
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
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-left: 8px;
        }
        .status-approved { background-color: #d4edda; color: #155724; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-under_review { background-color: #cce7ff; color: #004085; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
        .status-cancelled { background-color: #f5c6cb; color: #721c24; }
        .notes-section {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            border: 1px solid #e5e7eb;
        }
        .photos-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .photos-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .photos-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .photo-item {
            width: 100%;
            margin-bottom: 5px;
            page-break-inside: avoid;
        }
        .photo-item img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-left">
                    @php
                        $logoPath = public_path('assets/images/logo.png');
                        if (!file_exists($logoPath)) {
                            $logoPath = public_path('assets/images/logo.svg');
                        }
                        $logoUrl = file_exists($logoPath) ? 'file://' . str_replace('\\', '/', $logoPath) : null;

                        $companyName = \App\Models\Setting::get('company_name', config('app.name'));
                        $companyCnpj = \App\Models\Setting::get('company_cnpj', '');
                        $companyAddress = \App\Models\Setting::get('company_address', '');
                        $companyPhone = \App\Models\Setting::get('company_phone', '');
                        $companyEmail = \App\Models\Setting::get('company_email', '');
                    @endphp
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="Logo" class="logo" />
                    @endif
                    <div class="company-info">
                        <div class="company-name">{{ $companyName }}</div>
                        @if($companyCnpj)
                            <div><strong>CNPJ:</strong> {{ $companyCnpj }}</div>
                        @endif
                        @if($companyAddress)
                            <div><strong>Endereço:</strong> {{ $companyAddress }}</div>
                        @endif
                        @if($companyPhone)
                            <div><strong>Telefone:</strong> {{ $companyPhone }}</div>
                        @endif
                        @if($companyEmail)
                            <div><strong>E-mail:</strong> {{ $companyEmail }}</div>
                        @endif
                    </div>
                </td>
                <td class="header-right">
                    <div class="client-title">DADOS DO CLIENTE</div>
                    <div class="client-info">
                        @if($budget->client)
                            <div><strong>Nome/Razão Social:</strong> {{ $budget->client->name ?? $budget->client->trading_name }}</div>
                            @if($budget->client->trading_name && $budget->client->name)
                                <div><strong>Nome Fantasia:</strong> {{ $budget->client->trading_name }}</div>
                            @endif
                            @if($budget->client->cpf)
                                <div><strong>CPF:</strong> {{ $budget->client->formatted_cpf ?? $budget->client->cpf }}</div>
                            @endif
                            @if($budget->client->cnpj)
                                <div><strong>CNPJ:</strong> {{ $budget->client->formatted_cnpj ?? $budget->client->cnpj }}</div>
                            @endif
                            @if($budget->client->email)
                                <div><strong>E-mail:</strong> {{ $budget->client->email }}</div>
                            @endif
                            @if($budget->client->phone)
                                <div><strong>Telefone:</strong> {{ $budget->client->phone }}</div>
                            @endif
                            @if(method_exists($budget->client, 'getAttribute') && $budget->client->full_address)
                                <div><strong>Endereço:</strong> {{ $budget->client->full_address }}</div>
                            @endif
                            @if($budget->client->zip_code ?? false)
                                <div><strong>CEP:</strong> {{ $budget->client->zip_code }}</div>
                            @endif
                        @else
                            <div>Cliente não especificado</div>
                        @endif
        </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="title">
        Orçamento
        <span class="status-badge status-{{ $budget->status }}">{{ $budget->status_label }}</span>
    </div>

    <div class="info">
        <div class="info-row">
            <span class="label">Número do Orçamento:</span>
            <span class="value">#{{ $budget->id }} - Versão {{ $budget->version }}</span>
        </div>
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
                        @elseif($item->item_type === 'product' && $item->product)
                            @php
                                // Priorizar preço de revenda do produto (sale_price);
                                // se não houver, usar o preço unitário do item (valor do orçamento).
                                $unitPrice = $item->product->sale_price ?? $item->unit_price;
                            @endphp
                            R$ {{ number_format($unitPrice, 2, ',', '.') }}
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

    <!-- Photos Section -->
    @if($budget->photos && count($budget->photos) > 0)
        <div class="photos-section">
            <div class="photos-title">Fotos do Orçamento</div>
            <div class="photos-grid">
                @foreach($budget->photos as $photo)
                    @php
                        $photoPath = storage_path('app/public/' . $photo);
                        if (file_exists($photoPath)) {
                            $imageData = base64_encode(file_get_contents($photoPath));
                            $imageInfo = getimagesize($photoPath);
                            $mimeType = $imageInfo['mime'];
                            $imageSrc = 'data:' . $mimeType . ';base64,' . $imageData;
                        } else {
                            $imageSrc = '';
                        }
                    @endphp
                    @if($imageSrc)
                        <div class="photo-item">
                            <img src="{{ $imageSrc }}" alt="Foto do orçamento" style="width: 100%; height: auto; max-height: 400px; object-fit: contain;">
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

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
