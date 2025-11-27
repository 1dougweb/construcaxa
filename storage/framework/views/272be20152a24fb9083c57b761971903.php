<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <title>Vistoria #<?php echo e($inspection->number); ?></title>
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
                    <?php
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
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logoUrl): ?>
                        <img src="<?php echo e($logoUrl); ?>" alt="Logo" class="logo" />
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="company-info">
                        <div class="company-name"><?php echo e($companyName); ?></div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($companyCnpj): ?>
                            <div><strong>CNPJ:</strong> <?php echo e($companyCnpj); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($companyAddress): ?>
                            <div><strong>Endereço:</strong> <?php echo e($companyAddress); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($companyPhone): ?>
                            <div><strong>Telefone:</strong> <?php echo e($companyPhone); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($companyEmail): ?>
                            <div><strong>E-mail:</strong> <?php echo e($companyEmail); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </td>
                <td class="header-right">
                    <div class="client-title">DADOS DO CLIENTE</div>
                    <div class="client-info">
                        <div><strong>Nome/Razão Social:</strong> <?php echo e($inspection->client->name ?? $inspection->client->trading_name); ?></div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->client->trading_name && $inspection->client->name): ?>
                            <div><strong>Nome Fantasia:</strong> <?php echo e($inspection->client->trading_name); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->client->cpf): ?>
                            <div><strong>CPF:</strong> <?php echo e($inspection->client->formatted_cpf); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->client->cnpj): ?>
                            <div><strong>CNPJ:</strong> <?php echo e($inspection->client->formatted_cnpj); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->client->email): ?>
                            <div><strong>E-mail:</strong> <?php echo e($inspection->client->email); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->client->phone): ?>
                            <div><strong>Telefone:</strong> <?php echo e($inspection->client->phone); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->client->full_address): ?>
                            <div><strong>Endereço:</strong> <?php echo e($inspection->client->full_address); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->client->zip_code): ?>
                            <div><strong>CEP:</strong> <?php echo e($inspection->client->zip_code); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="title">Vistoria Técnica</div>
    
    <div class="info">
        <div class="info-row">
            <span class="label">Número da Vistoria:</span>
            <span><?php echo e($inspection->number); ?></span>
        </div>
        <div class="info-row">
            <span class="label">Data da Vistoria:</span>
            <span><?php echo e($inspection->inspection_date->format('d/m/Y')); ?></span>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->address): ?>
            <div class="info-row">
                <span class="label">Endereço da Vistoria:</span>
                <span><?php echo e($inspection->address); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->description): ?>
            <div class="info-row">
                <span class="label">Descrição:</span>
                <span><?php echo e($inspection->description); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->user): ?>
            <div class="info-row">
                <span class="label">Responsável pela Vistoria:</span>
                <span><?php echo e($inspection->user->name); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $inspection->environments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $environment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="environment">
            <div class="environment-title"><?php echo e($environment->name); ?></div>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($environment->items->count() > 0): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $environment->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="item">
                        <div class="item-title">
                            <?php echo e($item->title); ?>

                        </div>
                        
                        <!-- Sub-items -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->subItems->count() > 0): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $item->subItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="sub-item">
                                    <div class="sub-item-title">
                                        <?php echo e($subItem->title); ?>

                                        <span class="quality-badge quality-<?php echo e($subItem->quality_rating); ?>">
                                            <?php echo e($subItem->quality_label); ?>

                                        </span>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subItem->description): ?>
                                        <div style="margin-top: 3px; font-size: 10px; color: #666;">
                                            <?php echo e($subItem->description); ?>

                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subItem->observations): ?>
                                        <div style="margin-top: 3px; font-size: 10px; color: #666; font-style: italic;">
                                            <strong>Obs:</strong> <?php echo e($subItem->observations); ?>

                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->photos->count() > 0): ?>
                            <div class="photos">
                                <strong>Fotos:</strong>
                                <div class="photo-grid">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $item->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $imagePath = storage_path('app/public/' . $photo->photo_path);
                                            $imageUrl = file_exists($imagePath) ? 'file://' . str_replace('\\', '/', $imagePath) : null;
                                        ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($imageUrl): ?>
                                            <img src="<?php echo e($imageUrl); ?>" class="photo-item" alt="Foto">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php else: ?>
                <div style="padding: 10px; color: #666;">
                    Nenhum item cadastrado neste ambiente.
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->notes): ?>
        <div class="info" style="margin-top: 30px;">
            <div class="label">Observações Gerais:</div>
            <div><?php echo e($inspection->notes); ?></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="footer">
        <p>Este documento foi gerado automaticamente pelo sistema em <?php echo e(now()->format('d/m/Y H:i:s')); ?></p>
    </div>
</body>
</html>

<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/inspections/pdf.blade.php ENDPATH**/ ?>