<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Estoque</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
        }
        .header p {
            font-size: 12px;
            color: #666;
            margin: 5px 0;
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
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        .status {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-normal {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-low {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .status-out {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Estoque</h1>
        <p>Gerado em: <?php echo e(now()->format('d/m/Y H:i:s')); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Categoria</th>
                <th>Fornecedor</th>
                <th>Estoque Atual</th>
                <th>Estoque Mínimo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($product->name); ?></td>
                    <td><?php echo e($product->category->name); ?></td>
                    <td><?php echo e($product->supplier->company_name); ?></td>
                    <td><?php echo e($product->stock); ?></td>
                    <td><?php echo e($product->min_stock); ?></td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->stock <= 0): ?>
                            <span class="status status-out">Sem Estoque</span>
                        <?php elseif($product->stock <= $product->min_stock): ?>
                            <span class="status status-low">Estoque Baixo</span>
                        <?php else: ?>
                            <span class="status status-normal">Normal</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Nenhum produto encontrado.</td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Este relatório foi gerado automaticamente pelo sistema de gestão de estoque.</p>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/reports/stock-pdf.blade.php ENDPATH**/ ?>