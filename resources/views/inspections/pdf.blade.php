<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Vistoria #{{ $inspection->number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 8px;
            display: flex;
        }
        .label {
            font-weight: bold;
            width: 150px;
        }
        .value {
            flex: 1;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .photos-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 10px;
        }
        .photo-item {
            border: 1px solid #ddd;
            padding: 5px;
        }
        .photo-item img {
            width: 100%;
            height: auto;
            max-height: 150px;
            object-fit: cover;
        }
        .signature {
            margin-top: 80px;
            page-break-inside: avoid;
        }
        .signature-line {
            display: inline-block;
            width: 300px;
            border-top: 1px solid #000;
            margin-top: 60px;
            padding-top: 5px;
            text-align: center;
        }
        .signature-name {
            font-weight: bold;
            margin-top: 5px;
        }
        .signature-role {
            font-size: 10px;
            color: #666;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">VISTORIA TÉCNICA</div>
        <div>Número: {{ $inspection->number }}</div>
        <div>Versão: {{ $inspection->version }}</div>
    </div>

    <!-- Informações do Cliente -->
    <div class="section">
        <div class="section-title">DADOS DO CLIENTE</div>
        <div class="info">
            <div class="info-row">
                <span class="label">Nome:</span>
                <span class="value">{{ $inspection->client->name }}</span>
            </div>
            @if($inspection->client->trading_name)
            <div class="info-row">
                <span class="label">Nome Fantasia:</span>
                <span class="value">{{ $inspection->client->trading_name }}</span>
            </div>
            @endif
            @if($inspection->client->cpf)
            <div class="info-row">
                <span class="label">CPF:</span>
                <span class="value">{{ $inspection->client->formatted_cpf }}</span>
            </div>
            @endif
            @if($inspection->client->cnpj)
            <div class="info-row">
                <span class="label">CNPJ:</span>
                <span class="value">{{ $inspection->client->formatted_cnpj }}</span>
            </div>
            @endif
            @if($inspection->client->email)
            <div class="info-row">
                <span class="label">Email:</span>
                <span class="value">{{ $inspection->client->email }}</span>
            </div>
            @endif
            @if($inspection->client->phone)
            <div class="info-row">
                <span class="label">Telefone:</span>
                <span class="value">{{ $inspection->client->phone }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Informações da Vistoria -->
    <div class="section">
        <div class="section-title">DADOS DA VISTORIA</div>
        <div class="info">
            <div class="info-row">
                <span class="label">Data:</span>
                <span class="value">{{ $inspection->inspection_date->format('d/m/Y') }}</span>
            </div>
            @if($inspection->address)
            <div class="info-row">
                <span class="label">Endereço:</span>
                <span class="value">{{ $inspection->address }}</span>
            </div>
            @endif
            @if($inspection->inspector)
            <div class="info-row">
                <span class="label">Responsável:</span>
                <span class="value">{{ $inspection->inspector->name }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="label">Status:</span>
                <span class="value">{{ $inspection->status_label }}</span>
            </div>
        </div>
    </div>

    <!-- Descrição -->
    @if($inspection->description)
    <div class="section">
        <div class="section-title">DESCRIÇÃO / OBSERVAÇÕES</div>
        <div class="info">
            <p style="text-align: justify;">{{ $inspection->description }}</p>
        </div>
    </div>
    @endif

    <!-- Fotos -->
    @if($inspection->photos && count($inspection->photos) > 0)
    <div class="section">
        <div class="section-title">FOTOS</div>
        <div class="photos-grid">
            @foreach($inspection->photos as $photo)
                <div class="photo-item">
                    <img src="{{ public_path('storage/' . $photo) }}" alt="Foto da vistoria">
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Observações Adicionais -->
    @if($inspection->notes)
    <div class="section">
        <div class="section-title">OBSERVAÇÕES ADICIONAIS</div>
        <div class="info">
            <p style="text-align: justify;">{{ $inspection->notes }}</p>
        </div>
    </div>
    @endif

    <!-- Assinatura -->
    <div class="signature">
        <div style="text-align: center;">
            <div class="signature-line">
                <div class="signature-name">{{ $inspection->client->name }}</div>
                <div class="signature-role">Cliente</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Documento gerado em {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Este documento foi gerado automaticamente pelo sistema de gestão.</p>
    </div>
</body>
</html>



