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
            page-break-inside: avoid;
        }
        .environment-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            background-color: #f3f4f6;
            padding: 8px;
        }
        .item {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            page-break-inside: avoid;
        }
        .item-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .quality-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            margin-left: 10px;
        }
        .quality-excellent {
            background-color: #d1fae5;
            color: #065f46;
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
        .quality-very_good {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .sub-item {
            margin-top: 8px;
            margin-left: 15px;
            padding: 8px;
            background-color: #f9fafb;
            border-left: 3px solid #3b82f6;
        }
        .sub-item-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 3px;
        }
        .photos {
            margin-top: 10px;
        }
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
            margin-top: 5px;
        }
        .photo-item {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border: 1px solid #ddd;
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
            
            @if($environment->items->count() > 0)
                @foreach($environment->items as $item)
                    <div class="item">
                        <div class="item-title">
                            {{ $item->title }}
                        </div>
                        
                        <!-- Sub-items -->
                        @if($item->subItems->count() > 0)
                            @foreach($item->subItems as $subItem)
                                <div class="sub-item">
                                    <div class="sub-item-title">
                                        {{ $subItem->title }}
                                        <span class="quality-badge quality-{{ $subItem->quality_rating }}">
                                            {{ $subItem->quality_label }}
                                        </span>
                                    </div>
                                    @if($subItem->description)
                                        <div style="margin-top: 3px; font-size: 10px; color: #666;">
                                            {{ $subItem->description }}
                                        </div>
                                    @endif
                                    @if($subItem->observations)
                                        <div style="margin-top: 3px; font-size: 10px; color: #666; font-style: italic;">
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
                                            $imagePath = public_path($photo->photo_path);
                                            $imageUrl = file_exists($imagePath) ? 'file://' . str_replace('\\', '/', $imagePath) : null;
                                        @endphp
                                        @if($imageUrl)
                                            <img src="{{ $imageUrl }}" class="photo-item" alt="Foto">
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

