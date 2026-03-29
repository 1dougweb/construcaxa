<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <title>Vistoria #{{ $inspection->number }}</title>
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
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 15px;
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
            border-left: 1px solid #ddd;
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
        .info-row {
            margin-bottom: 5px;
        }
        .label {
            font-weight: bold;
        }
        .environment {
            margin-bottom: 30px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            page-break-inside: avoid;
        }
        .environment-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            background-color: #1e40af;
            color: #ffffff;
            padding: 12px 15px;
            margin: 0;
            border-bottom: 1px solid #1e40af;
        }
        .environment-content {
            padding: 15px;
            background-color: #ffffff;
        }
        .item {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #e5e7eb;
            page-break-inside: avoid;
        }
        .item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .item-title {
            font-size: 11px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 12px;
            padding-left: 5px;
            border-left: 4px solid #94a3b8;
            text-transform: uppercase;
        }
        .quality-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .quality-excellent {
            background-color: #065f46;
            color: #ffffff;
        }
        .quality-very_good {
            background-color: #1e40af;
            color: #ffffff;
        }
        .quality-good {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .quality-regular {
            background-color: #fef3c7;
            color: #92400e;
        }
        .quality-poor {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .sub-item {
            margin-top: 10px;
            margin-left: 5px;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #eeeeee;
        }
        .sub-item-excellent { background-color: #f0fdf4; }
        .sub-item-very_good { background-color: #f0f9ff; }
        .sub-item-good { background-color: #f0f9ff; }
        .sub-item-regular { background-color: #fffbeb; }
        .sub-item-poor { background-color: #fef2f2; }
        .sub-item-table {
            width: 100%;
            border-collapse: collapse;
        }
        .sub-item-title {
            font-weight: bold;
            font-size: 11px;
            color: #111827;
        }
        .sub-item-desc {
            font-size: 10px;
            color: #4b5563;
            margin-top: 4px;
        }
        .sub-item-obs {
            font-size: 9px;
            color: #6b7280;
            font-style: italic;
            margin-top: 8px;
            padding: 8px;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
        }
        .photos {
            margin-top: 10px;
        }
        .photo-grid {
            margin-top: 10px;
            width: 100%;
        }
        .photo-wrapper {
            display: inline-block;
            width: 220px;
            height: 220px;
            margin-right: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            overflow: hidden;
            vertical-align: top;
            background-color: #ffffff;
        }
        .photo-img {
            width: 220px;
            height: 220px;
            object-fit: cover;
            background-color: #ffffff;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
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
                        // Usar caminho absoluto para o DomPDF
                        $logoUrl = file_exists($logoPath) ? 'file://' . str_replace('\\', '/', $logoPath) : null;
                        
                        // Dados da empresa
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
                        <div><strong>Nome/Razão Social:</strong> {{ $inspection->client->name ?? $inspection->client->trading_name }}</div>
                        @if($inspection->client->trading_name && $inspection->client->name)
                            <div><strong>Nome Fantasia:</strong> {{ $inspection->client->trading_name }}</div>
                        @endif
                        @if($inspection->client->cpf)
                            <div><strong>CPF:</strong> {{ $inspection->client->formatted_cpf }}</div>
                        @endif
                        @if($inspection->client->cnpj)
                            <div><strong>CNPJ:</strong> {{ $inspection->client->formatted_cnpj }}</div>
                        @endif
                        @if($inspection->client->email)
                            <div><strong>E-mail:</strong> {{ $inspection->client->email }}</div>
                        @endif
                        @if($inspection->client->phone)
                            <div><strong>Telefone:</strong> {{ $inspection->client->phone }}</div>
                        @endif
                        @if($inspection->client->full_address)
                            <div><strong>Endereço:</strong> {{ $inspection->client->full_address }}</div>
                        @endif
                        @if($inspection->client->zip_code)
                            <div><strong>CEP:</strong> {{ $inspection->client->zip_code }}</div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="title">Vistoria Técnica</div>
    
    <div class="info">
        <div class="info-row">
            <span class="label">Número da Vistoria:</span>
            <span>{{ $inspection->number }}</span>
        </div>
        <div class="info-row">
            <span class="label">Data da Vistoria:</span>
            <span>{{ $inspection->inspection_date->format('d/m/Y') }}</span>
        </div>
        @if($inspection->address)
            <div class="info-row">
                <span class="label">Endereço da Vistoria:</span>
                <span>{{ $inspection->address }}</span>
            </div>
        @endif
        @if($inspection->description)
            <div class="info-row">
                <span class="label">Descrição:</span>
                <span>{{ $inspection->description }}</span>
            </div>
        @endif
        @if($inspection->user)
            <div class="info-row">
                <span class="label">Responsável pela Vistoria:</span>
                <span>{{ $inspection->user->name }}</span>
            </div>
        @endif
    </div>

    @foreach($inspection->environments as $environment)
        <div class="environment">
            <div class="environment-title">{{ $environment->name }}</div>
            <div class="environment-content">
                @if($environment->items->count() > 0)
                    @foreach($environment->items as $item)
                        <div class="item">
                            @if(trim(strtolower($item->title)) !== trim(strtolower($environment->name)))
                                <div class="item-title">
                                    {{ $item->title }}
                                </div>
                            @endif
                            
                            <!-- Sub-items -->
                            @if($item->subItems->count() > 0)
                                @foreach($item->subItems as $subItem)
                                    <div class="sub-item sub-item-{{ $subItem->quality_rating }}">
                                        <table class="sub-item-table">
                                            <tr>
                                                <td class="sub-item-title">{{ $subItem->title }}</td>
                                                <td style="text-align: right; width: 100px;">
                                                    <span class="quality-badge quality-{{ $subItem->quality_rating }}">
                                                        {{ $subItem->quality_label }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        @if($subItem->description)
                                            <div class="sub-item-desc">
                                                {{ $subItem->description }}
                                            </div>
                                        @endif
                                        
                                        @if($subItem->observations)
                                            <div class="sub-item-obs">
                                                <strong>Obs:</strong> {{ $subItem->observations }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif

                            @if($item->photos->count() > 0)
                                <div class="photos">
                                    <strong>Fotos:</strong>
                                    <div class="photo-grid">
                                        @foreach($item->photos as $photo)
                                            @php
                                                $imagePath = public_path('storage/' . $photo->photo_path);
                                                $imageUrl = file_exists($imagePath) ? 'file://' . str_replace('\\', '/', $imagePath) : null;
                                            @endphp
                                            @if($imageUrl)
                                                <div class="photo-wrapper">
                                                    <img src="{{ $imageUrl }}" class="photo-img" alt="Foto">
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div style="padding: 10px; color: #666;">
                        Nenhum item cadastrado neste ambiente.
                    </div>
                @endif
            </div>
        </div>
    @endforeach

    @if($inspection->notes)
        <div class="info" style="margin-top: 30px;">
            <div class="label">Observações Gerais:</div>
            <div>{{ $inspection->notes }}</div>
        </div>
    @endif

    <div class="footer">
        <p>Este documento foi gerado automaticamente pelo sistema em {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>

