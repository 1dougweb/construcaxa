<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Orçamento Disponível</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f3f4f6;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 24px;
            border: 1px solid #e5e7eb;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4F46E5;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
            font-weight: bold;
        }
        .button-primary {
            background-color: #10b981;
        }
        .info-box {
            background-color: white;
            padding: 16px;
            border-radius: 6px;
            margin: 15px 0;
            border: 1px solidrgb(182, 181, 209);
        }
        .footer {
            text-align: center;
            padding: 16px;
            color: #6b7280;
            font-size: 12px;
        }
        .item-list {
            background-color: white;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .item-row {
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        .item-row:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Novo Orçamento Disponível</h1>
    </div>
    
    <div class="content">
        <p>Olá <strong><?php echo e($budget->client?->name ?? 'cliente'); ?></strong>,</p>
        
        <p>Acabamos de gerar um novo orçamento para você em <strong><?php echo e(config('app.name')); ?></strong>.</p>

        <div class="info-box">
            <h3>Resumo do Orçamento</h3>
            <p><strong>ID do Orçamento:</strong> #<?php echo e($budget->id); ?></p>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->inspection): ?>
                <p><strong>Vistoria Relacionada:</strong> <?php echo e($budget->inspection->number); ?></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <p><strong>Versão:</strong> <?php echo e($budget->version); ?></p>
            <p><strong>Status Atual:</strong> <?php echo e($budget->status_label); ?></p>
            <p><strong>Subtotal:</strong> R$ <?php echo e(number_format($budget->subtotal, 2, ',', '.')); ?></p>
            <p><strong>Desconto:</strong> R$ <?php echo e(number_format($budget->discount, 2, ',', '.')); ?></p>
            <p><strong>Total:</strong> <strong>R$ <?php echo e(number_format($budget->total, 2, ',', '.')); ?></strong></p>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->notes): ?>
            <div class="info-box">
                <h3>Observações</h3>
                <p><?php echo e($budget->notes); ?></p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->items && $budget->items->count() > 0): ?>
            <div class="item-list">
                <h3>Principais Itens</h3>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $budget->items->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="item-row">
                        <strong><?php echo e(ucfirst($item->item_type)); ?>:</strong> <?php echo e($item->description); ?><br>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->quantity): ?>
                            Quantidade: <?php echo e(number_format($item->quantity, 2, ',', '.')); ?> –
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        Valor: R$ <?php echo e(number_format($item->total, 2, ',', '.')); ?>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->items->count() > 5): ?>
                    <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">
                        (+ <?php echo e($budget->items->count() - 5); ?> itens adicionais)
                    </p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <p style="text-align: center; margin-top: 24px; color:">
            <a href="<?php echo e(url('/')); ?>" class="button button-primary" style="color: white;">
                Acessar sistema para analisar e responder este orçamento
            </a>
        </p>

        <p style="margin-top: 20px; font-size: 14px; color: #6b7280;">
            Caso você tenha dúvidas ou precise de ajustes neste orçamento, basta responder este e-mail ou entrar em contato com nossa equipe.
        </p>
    </div>
    
    <div class="footer">
        <p>Este é um email automático, por favor não responda diretamente.</p>
        <p>&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. Todos os direitos reservados.</p>
    </div>
</body>
</html>


<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/emails/budgets/client-request.blade.php ENDPATH**/ ?>