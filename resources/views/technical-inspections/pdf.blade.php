<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Vistoria Técnica #{{ $inspection->number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 15px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            color: #64748b;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-row {
            margin-bottom: 8px;
            display: table;
            width: 100%;
        }
        .info-label {
            font-weight: bold;
            display: table-cell;
            width: 150px;
            color: #475569;
        }
        .info-value {
            display: table-cell;
            color: #1e293b;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            margin-top: 25px;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #cbd5e1;
        }
        .environment-block {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .environment-name {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 10px;
            background-color: #f1f5f9;
            padding: 8px;
            border-left: 4px solid #3b82f6;
        }
        .environment-notes {
            margin-bottom: 10px;
            color: #475569;
            font-style: italic;
        }
        .photos-grid {
            margin: 10px 0;
            display: table;
            width: 100%;
        }
        .photo-item {
            display: table-cell;
            width: 33.33%;
            padding: 5px;
            vertical-align: top;
        }
        .photo-item img {
            width: 100%;
            height: auto;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .element-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        .element-table th {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            color: #475569;
        }
        .element-table td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            font-size: 10px;
            color: #1e293b;
        }
        .condition-bubble {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 5px;
        }
        .condition-poor { background-color: #DC2626; }
        .condition-fair { background-color: #F59E0B; }
        .condition-good { background-color: #10B981; }
        .condition-very-good { background-color: #3B82F6; }
        .condition-excellent { background-color: #059669; }
        .qr-code {
            text-align: center;
            margin: 15px 0;
        }
        .qr-code img {
            max-width: 150px;
            height: auto;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 9px;
            color: #64748b;
        }
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 20px;
        }
        .signature-line {
            border-top: 1px solid #1e293b;
            margin-top: 50px;
            padding-top: 5px;
            font-size: 10px;
        }
        .map-image {
            max-width: 100%;
            height: auto;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">VISTORIA TÉCNICA</div>
        <div class="subtitle">Número: {{ $inspection->number }}</div>
    </div>

    <!-- Informações Gerais -->
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Data da Vistoria:</span>
            <span class="info-value">{{ $inspection->inspection_date->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Endereço:</span>
            <span class="info-value">{{ $inspection->address }}</span>
        </div>
        @if($inspection->unit_area)
        <div class="info-row">
            <span class="info-label">Metragem:</span>
            <span class="info-value">{{ number_format($inspection->unit_area, 2, ',', '.') }} m²</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Responsável Técnico:</span>
            <span class="info-value">{{ $inspection->responsible_name }}</span>
        </div>
        @if($inspection->involved_parties)
        <div class="info-row">
            <span class="info-label">Intervenientes:</span>
            <span class="info-value">{{ $inspection->involved_parties }}</span>
        </div>
        @endif
        @if($inspection->furniture_status)
        <div class="info-row">
            <span class="info-label">Situação da Mobília:</span>
            <span class="info-value">{{ $inspection->furniture_status }}</span>
        </div>
        @endif
        @if($inspection->client)
        <div class="info-row">
            <span class="info-label">Cliente:</span>
            <span class="info-value">{{ $inspection->client->name }}</span>
        </div>
        @endif
        @if($inspection->project)
        <div class="info-row">
            <span class="info-label">Projeto:</span>
            <span class="info-value">{{ $inspection->project->name }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Total de Fotos:</span>
            <span class="info-value">{{ $inspection->total_photos_count }}</span>
        </div>
    </div>

    <!-- Mapa -->
    @if($inspection->map_image_path && file_exists(storage_path('app/public/' . $inspection->map_image_path)))
    <div class="info-section">
        <div class="section-title">Localização</div>
        <img src="{{ storage_path('app/public/' . $inspection->map_image_path) }}" alt="Mapa" class="map-image">
    </div>
    @endif

    <!-- Ambientes -->
    <div class="section-title">AVALIAÇÃO TÉCNICA - AMBIENTES</div>

    @foreach($inspection->environments as $environment)
        <div class="environment-block">
            <div class="environment-name">{{ $environment->name }}</div>

            @if($environment->technical_notes)
            <div class="environment-notes">{{ $environment->technical_notes }}</div>
            @endif

            @if($environment->photos && count($environment->photos) > 0)
            <div class="photos-grid">
                @foreach(array_slice($environment->photos, 0, 3) as $photo)
                    @if(file_exists(storage_path('app/public/' . $photo)))
                    <div class="photo-item">
                        <img src="{{ storage_path('app/public/' . $photo) }}" alt="Foto">
                    </div>
                    @endif
                @endforeach
            </div>
            @endif

            @if($environment->google_drive_link)
            <div class="qr-code">
                <div style="margin-bottom: 5px; font-size: 9px; color: #64748b;">Link Google Drive:</div>
                <div style="font-size: 8px; word-break: break-all; color: #3b82f6;">{{ $environment->google_drive_link }}</div>
                @if($environment->qr_code_path && file_exists(storage_path('app/public/' . $environment->qr_code_path)))
                <img src="{{ storage_path('app/public/' . $environment->qr_code_path) }}" alt="QR Code">
                @endif
            </div>
            @endif

            @if($environment->measurements)
            <div style="margin: 10px 0; font-size: 10px; color: #475569;">
                <strong>Medidas:</strong> {{ $environment->measurements }}
            </div>
            @endif

            <!-- Elementos -->
            @if($environment->elements->count() > 0)
            <table class="element-table">
                <thead>
                    <tr>
                        <th>Elemento</th>
                        <th>Condição</th>
                        <th>Observações</th>
                        <th>Medidas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($environment->elements as $element)
                    <tr>
                        <td><strong>{{ $element->name }}</strong></td>
                        <td>
                            <span class="condition-bubble condition-{{ $element->condition_status }}"></span>
                            {{ $element->condition_label }}
                        </td>
                        <td>
                            {{ $element->technical_notes }}
                            @if($element->defects_identified)
                                <br><strong>Defeitos:</strong> {{ $element->defects_identified }}
                            @endif
                            @if($element->probable_causes)
                                <br><strong>Causas:</strong> {{ $element->probable_causes }}
                            @endif
                        </td>
                        <td>{{ $element->measurements ?: '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    @endforeach

    <!-- Rodapé -->
    <div class="footer">
        <div>Documento gerado em {{ now()->format('d/m/Y H:i') }}</div>
        <div style="margin-top: 30px;" class="signature-section">
            <div class="signature-box">
                <div class="signature-line">
                    Responsável Técnico<br>
                    {{ $inspection->responsible_name }}
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    Recebido por<br>
                    _________________________
                </div>
            </div>
        </div>
    </div>
</body>
</html>

