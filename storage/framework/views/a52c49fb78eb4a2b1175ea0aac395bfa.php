<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Requisição de Equipamento #<?php echo e($equipmentRequest->number); ?></title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
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
            margin-bottom: 5px;
        }
        .label {
            font-weight: bold;
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
        }
        th {
            background-color: #f3f4f6;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
        }
        .signature-line {
            display: inline-block;
            width: 250px;
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }
        .signature-name {
            font-weight: bold;
        }
        .signature-role {
            font-size: 10px;
            color: #666;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-approved { background-color: #dbeafe; color: #1e40af; }
        .status-rejected { background-color: #fecaca; color: #991b1b; }
        .status-completed { background-color: #d1fae5; color: #065f46; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Requisição de Equipamento</div>
        <div>Número: <?php echo e($equipmentRequest->number); ?></div>
    </div>

    <div class="info">
        <div class="info-row">
            <span class="label">Data:</span>
            <span><?php echo e($equipmentRequest->created_at->format('d/m/Y H:i')); ?></span>
        </div>
        <div class="info-row">
            <span class="label">Tipo:</span>
            <span><?php echo e($equipmentRequest->type === 'loan' ? 'Empréstimo' : 'Devolução'); ?></span>
        </div>
        <div class="info-row">
            <span class="label">Status:</span>
            <span class="status-badge status-<?php echo e($equipmentRequest->status); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($equipmentRequest->status):
                    case ('pending'): ?> Pendente <?php break; ?>
                    <?php case ('approved'): ?> Aprovado <?php break; ?>
                    <?php case ('rejected'): ?> Rejeitado <?php break; ?>
                    <?php case ('completed'): ?> Concluído <?php break; ?>
                <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </span>
        </div>
    </div>

    <div class="info">
        <div class="info-row">
            <span class="label">Funcionário Requisitante:</span>
            <span><?php echo e($equipmentRequest->employee->name); ?> - <?php echo e($equipmentRequest->employee->department); ?></span>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($equipmentRequest->employee->cpf): ?>
            <div class="info-row">
                <span class="label">CPF:</span>
                <span><?php echo e($equipmentRequest->employee->cpf); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($equipmentRequest->employee->phone): ?>
            <div class="info-row">
                <span class="label">WhatsApp:</span>
                <span><?php echo e($equipmentRequest->employee->phone); ?></span>
            </div>
        <?php elseif($equipmentRequest->employee->user->phone): ?>
            <div class="info-row">
                <span class="label">WhatsApp:</span>
                <span><?php echo e($equipmentRequest->employee->user->phone); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($equipmentRequest->serviceOrder): ?>
            <div class="info-row">
                <span class="label">Ordem de Serviço:</span>
                <span><?php echo e($equipmentRequest->serviceOrder->number); ?> - <?php echo e($equipmentRequest->serviceOrder->client_name); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($equipmentRequest->expected_return_date): ?>
    <div class="info">
        <div class="info-row">
            <span class="label">Data Prevista de Devolução:</span>
            <span><?php echo e($equipmentRequest->expected_return_date->format('d/m/Y')); ?></span>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($equipmentRequest->purpose): ?>
    <div class="info">
        <div class="label">Finalidade:</div>
        <div><?php echo e($equipmentRequest->purpose); ?></div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($equipmentRequest->notes): ?>
        <div class="info">
            <div class="label">Observações:</div>
            <div><?php echo e($equipmentRequest->notes); ?></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Equipamento</th>
                <th>Número de Série</th>
                <th>Quantidade</th>
                <th>Observações</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $equipmentRequest->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <?php echo e($item->equipment->name); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->equipment->category): ?>
                            <br><small><?php echo e($item->equipment->category->name); ?></small>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td><?php echo e($item->equipment->serial_number); ?></td>
                    <td><?php echo e($item->quantity); ?></td>
                    <td><?php echo e($item->condition_notes ?: '-'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    <div class="signature">
        <div class="signature-line">
            <div class="signature-name"><?php echo e($equipmentRequest->employee->name); ?></div>
            <div class="signature-role"><?php echo e($equipmentRequest->employee->position); ?> - <?php echo e($equipmentRequest->employee->department); ?></div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($equipmentRequest->employee->cpf): ?>
                <div class="signature-role">CPF: <?php echo e($equipmentRequest->employee->cpf); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <div class="footer">
        Documento gerado em <?php echo e(now()->format('d/m/Y H:i:s')); ?>

    </div>
</body>
</html>





<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/equipment-requests/pdf.blade.php ENDPATH**/ ?>