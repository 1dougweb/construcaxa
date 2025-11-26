<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Vistoria Técnica #<?php echo e($inspection->number); ?></title>
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
        <div class="subtitle">Número: <?php echo e($inspection->number); ?></div>
    </div>

    <!-- Informações Gerais -->
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Data da Vistoria:</span>
            <span class="info-value"><?php echo e($inspection->inspection_date->format('d/m/Y')); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Endereço:</span>
            <span class="info-value"><?php echo e($inspection->address); ?></span>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->unit_area): ?>
        <div class="info-row">
            <span class="info-label">Metragem:</span>
            <span class="info-value"><?php echo e(number_format($inspection->unit_area, 2, ',', '.')); ?> m²</span>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="info-row">
            <span class="info-label">Responsável Técnico:</span>
            <span class="info-value"><?php echo e($inspection->responsible_name); ?></span>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->involved_parties): ?>
        <div class="info-row">
            <span class="info-label">Intervenientes:</span>
            <span class="info-value"><?php echo e($inspection->involved_parties); ?></span>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->furniture_status): ?>
        <div class="info-row">
            <span class="info-label">Situação da Mobília:</span>
            <span class="info-value"><?php echo e($inspection->furniture_status); ?></span>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->client): ?>
        <div class="info-row">
            <span class="info-label">Cliente:</span>
            <span class="info-value"><?php echo e($inspection->client->name); ?></span>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->project): ?>
        <div class="info-row">
            <span class="info-label">Projeto:</span>
            <span class="info-value"><?php echo e($inspection->project->name); ?></span>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="info-row">
            <span class="info-label">Total de Fotos:</span>
            <span class="info-value"><?php echo e($inspection->total_photos_count); ?></span>
        </div>
    </div>

    <!-- Mapa -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->map_image_path && file_exists(storage_path('app/public/' . $inspection->map_image_path))): ?>
    <div class="info-section">
        <div class="section-title">Localização</div>
        <img src="<?php echo e(storage_path('app/public/' . $inspection->map_image_path)); ?>" alt="Mapa" class="map-image">
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Ambientes -->
    <div class="section-title">AVALIAÇÃO TÉCNICA - AMBIENTES</div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $inspection->environments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $environment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="environment-block">
            <div class="environment-name"><?php echo e($environment->name); ?></div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($environment->technical_notes): ?>
            <div class="environment-notes"><?php echo e($environment->technical_notes); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($environment->photos && count($environment->photos) > 0): ?>
            <div class="photos-grid">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = array_slice($environment->photos, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(storage_path('app/public/' . $photo))): ?>
                    <div class="photo-item">
                        <img src="<?php echo e(storage_path('app/public/' . $photo)); ?>" alt="Foto">
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($environment->google_drive_link): ?>
            <div class="qr-code">
                <div style="margin-bottom: 5px; font-size: 9px; color: #64748b;">Link Google Drive:</div>
                <div style="font-size: 8px; word-break: break-all; color: #3b82f6;"><?php echo e($environment->google_drive_link); ?></div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($environment->qr_code_path && file_exists(storage_path('app/public/' . $environment->qr_code_path))): ?>
                <img src="<?php echo e(storage_path('app/public/' . $environment->qr_code_path)); ?>" alt="QR Code">
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($environment->measurements): ?>
            <div style="margin: 10px 0; font-size: 10px; color: #475569;">
                <strong>Medidas:</strong> <?php echo e($environment->measurements); ?>

            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Elementos -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($environment->elements->count() > 0): ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $environment->elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><strong><?php echo e($element->name); ?></strong></td>
                        <td>
                            <span class="condition-bubble condition-<?php echo e($element->condition_status); ?>"></span>
                            <?php echo e($element->condition_label); ?>

                        </td>
                        <td>
                            <?php echo e($element->technical_notes); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($element->defects_identified): ?>
                                <br><strong>Defeitos:</strong> <?php echo e($element->defects_identified); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($element->probable_causes): ?>
                                <br><strong>Causas:</strong> <?php echo e($element->probable_causes); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td><?php echo e($element->measurements ?: '-'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Rodapé -->
    <div class="footer">
        <div>Documento gerado em <?php echo e(now()->format('d/m/Y H:i')); ?></div>
        <div style="margin-top: 30px;" class="signature-section">
            <div class="signature-box">
                <div class="signature-line">
                    Responsável Técnico<br>
                    <?php echo e($inspection->responsible_name); ?>

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

<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/technical-inspections/pdf.blade.php ENDPATH**/ ?>