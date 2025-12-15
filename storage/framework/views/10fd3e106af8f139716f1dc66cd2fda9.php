<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Orçamento #<?php echo e($budget->id); ?> - v<?php echo e($budget->version); ?></title>
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
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        .photo-item {
            width: 100%;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
        .photo-item img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Orçamento</div>
        <div class="subtitle">
            Número: #<?php echo e($budget->id); ?> - Versão <?php echo e($budget->version); ?>

            <span class="status-badge status-<?php echo e($budget->status); ?>"><?php echo e($budget->status_label); ?></span>
        </div>
    </div>

    <!-- Company Information -->
    <div class="company-info">
        <strong><?php echo e(config('app.name', 'Empresa')); ?></strong><br>
        <!-- Add your company details here -->
        Endereço da Empresa<br>
        Telefone: (00) 0000-0000 | Email: contato@empresa.com
    </div>

    <!-- Client Information -->
    <div class="info-section">
        <div class="info-title">Dados do Cliente</div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->client): ?>
            <div class="info-row">
                <span class="label">Nome:</span>
                <span class="value"><?php echo e($budget->client->name); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Email:</span>
                <span class="value"><?php echo e($budget->client->email); ?></span>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->client->phone): ?>
                <div class="info-row">
                    <span class="label">Telefone:</span>
                    <span class="value"><?php echo e($budget->client->phone); ?></span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php else: ?>
            <div class="info-row">
                <span class="value">Cliente não especificado</span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Project Information -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->project): ?>
    <div class="info-section">
        <div class="info-title">Dados do Projeto</div>
        <div class="info-row">
            <span class="label">Projeto:</span>
            <span class="value"><?php echo e($budget->project->name); ?></span>
        </div>
        <div class="info-row">
            <span class="label">Código:</span>
            <span class="value"><?php echo e($budget->project->code); ?></span>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->project->os_number): ?>
            <div class="info-row">
                <span class="label">OS Número:</span>
                <span class="value"><?php echo e($budget->project->os_number); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->project->address): ?>
            <div class="info-row">
                <span class="label">Endereço:</span>
                <span class="value"><?php echo e($budget->project->address); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Budget Information -->
    <div class="info-section">
        <div class="info-title">Informações do Orçamento</div>
        <div class="info-row">
            <span class="label">Data de Criação:</span>
            <span class="value"><?php echo e($budget->created_at->format('d/m/Y H:i')); ?></span>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->approved_at): ?>
            <div class="info-row">
                <span class="label">Data de Aprovação:</span>
                <span class="value"><?php echo e($budget->approved_at->format('d/m/Y H:i')); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->approver): ?>
            <div class="info-row">
                <span class="label">Aprovado por:</span>
                <span class="value"><?php echo e($budget->approver->name); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $budget->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <strong><?php echo e($item->description); ?></strong>
                        <br>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->item_type === 'product' && $item->product): ?>
                            <small style="color: #666;">Produto: <?php echo e($item->product->name); ?></small>
                        <?php elseif($item->item_type === 'service' && $item->service): ?>
                            <small style="color: #666;">Serviço: <?php echo e($item->service->name); ?> (<?php echo e($item->service->unit_type_label); ?>)</small>
                        <?php elseif($item->item_type === 'labor' && $item->laborType): ?>
                            <small style="color: #666;">Mão de Obra: <?php echo e($item->laborType->name); ?> (<?php echo e($item->laborType->skill_level_label); ?>)</small>
                        <?php else: ?>
                            <small style="color: #666;"><?php echo e($item->item_type_label); ?></small>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->item_type === 'labor'): ?>
                            <?php echo e($item->quantity_display); ?>

                        <?php else: ?>
                            <?php echo e(number_format($item->quantity, 3, ',', '.')); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td class="text-right">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->item_type === 'labor'): ?>
                            <?php echo e($item->laborType ? $item->laborType->formatted_hourly_rate : 'R$ ' . number_format($item->unit_price, 2, ',', '.') . '/h'); ?>

                        <?php else: ?>
                            R$ <?php echo e(number_format($item->unit_price, 2, ',', '.')); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td class="text-right">R$ <?php echo e(number_format($item->total, 2, ',', '.')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    <!-- Totals Section -->
    <div class="totals-section">
        <div class="total-row">
            <span class="total-label">Subtotal:</span>
            <span class="total-value">R$ <?php echo e(number_format($budget->subtotal, 2, ',', '.')); ?></span>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->discount > 0): ?>
            <div class="total-row">
                <span class="total-label">Desconto:</span>
                <span class="total-value">-R$ <?php echo e(number_format($budget->discount, 2, ',', '.')); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="total-row grand-total">
            <span class="total-label">TOTAL GERAL:</span>
            <span class="total-value">R$ <?php echo e(number_format($budget->total, 2, ',', '.')); ?></span>
        </div>
    </div>

    <!-- Photos Section -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->photos && count($budget->photos) > 0): ?>
        <div class="photos-section">
            <div class="photos-title">Fotos do Orçamento</div>
            <div class="photos-grid">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $budget->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $photoPath = storage_path('app/public/' . $photo);
                        if (file_exists($photoPath)) {
                            $imageData = base64_encode(file_get_contents($photoPath));
                            $imageInfo = getimagesize($photoPath);
                            $mimeType = $imageInfo['mime'];
                            $imageSrc = 'data:' . $mimeType . ';base64,' . $imageData;
                        } else {
                            $imageSrc = '';
                        }
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($imageSrc): ?>
                        <div class="photo-item">
                            <img src="<?php echo e($imageSrc); ?>" alt="Foto do orçamento" style="width: 100%; height: auto; max-height: 400px; object-fit: contain;">
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Notes Section -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->notes): ?>
        <div class="notes-section">
            <div class="info-title">Observações</div>
            <div><?php echo e($budget->notes); ?></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Footer -->
    <div class="footer">
        <p>Este orçamento foi gerado automaticamente pelo sistema em <?php echo e(now()->format('d/m/Y H:i:s')); ?></p>
        <p>Orçamento válido por 30 dias a partir da data de emissão.</p>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/budgets/pdf.blade.php ENDPATH**/ ?>