<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vistoria #<?php echo e($inspection->number); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-indigo-600 text-white px-6 py-4">
                    <h1 class="text-2xl font-bold">Vistoria #<?php echo e($inspection->number); ?></h1>
                    <p class="text-indigo-100 mt-1">Cliente: <?php echo e($inspection->client->name ?? $inspection->client->trading_name); ?></p>
                    <p class="text-indigo-100">Data: <?php echo e($inspection->inspection_date->format('d/m/Y')); ?></p>
                </div>

                <!-- Content -->
                <div class="px-6 py-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->address): ?>
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold mb-2">Endereço</h2>
                            <p class="text-gray-700"><?php echo e($inspection->address); ?></p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->latitude && $inspection->longitude): ?>
                                <div id="map" class="mt-4 w-full h-64 rounded-lg border border-gray-300"></div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->description): ?>
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold mb-2">Descrição</h2>
                            <p class="text-gray-700"><?php echo e($inspection->description); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Ambientes -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $inspection->environments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $environment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-8 border border-gray-200 rounded-lg p-4">
                            <h2 class="text-xl font-semibold mb-4"><?php echo e($environment->name); ?></h2>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $environment->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="mb-6 pb-6 border-b border-gray-200 last:border-b-0">
                                    <h3 class="text-lg font-medium mb-3"><?php echo e($item->title); ?></h3>
                                    
                                    <!-- Sub-items -->
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->subItems->count() > 0): ?>
                                        <div class="space-y-3 mb-4">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $item->subItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="bg-gray-50 p-3 rounded-lg">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <h4 class="font-medium text-gray-900"><?php echo e($subItem->title); ?></h4>
                                                        <span class="px-2 py-1 text-xs rounded <?php echo e($subItem->quality_rating === 'excellent' ? 'bg-green-100 text-green-800' : 
                                                            ($subItem->quality_rating === 'very_good' ? 'bg-blue-100 text-blue-800' : 
                                                            ($subItem->quality_rating === 'good' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'))); ?>">
                                                            <?php echo e($subItem->quality_label); ?>

                                                        </span>
                                                    </div>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subItem->description): ?>
                                                        <p class="text-sm text-gray-600 mb-1"><?php echo e($subItem->description); ?></p>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subItem->observations): ?>
                                                        <p class="text-sm text-gray-700 italic"><?php echo e($subItem->observations); ?></p>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <!-- Fotos -->
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->photos->count() > 0): ?>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $item->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <img src="<?php echo e(asset('storage/' . $photo->photo_path)); ?>" alt="Foto" class="w-full h-32 object-cover rounded">
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Decisão do cliente -->
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h2 class="text-lg font-semibold mb-4">Sua decisão sobre esta vistoria</h2>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->client_decision === 'approved'): ?>
                            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                                Esta vistoria foi aprovada em <?php echo e(optional($inspection->client_decision_at)->format('d/m/Y H:i')); ?>.
                            </div>
                        <?php elseif($inspection->client_decision === 'contested'): ?>
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded space-y-2">
                                <p>Esta vistoria foi contestada em <?php echo e(optional($inspection->client_decision_at)->format('d/m/Y H:i')); ?>.</p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->client_comment): ?>
                                    <p><strong>Comentário registrado:</strong></p>
                                    <p class="whitespace-pre-line"><?php echo e($inspection->client_comment); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php elseif($inspection->status === 'completed'): ?>
                            <p class="text-sm text-gray-600 mb-4">
                                Após revisar todas as informações acima, escolha uma das opções abaixo.
                                Sua decisão será registrada uma única vez para esta vistoria.
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Aprovar -->
                                <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                                    <h3 class="font-semibold text-green-800 mb-2">Aprovar vistoria</h3>
                                    <p class="text-sm text-green-900 mb-3">
                                        Use esta opção se você concorda com o conteúdo da vistoria e não tem ajustes a solicitar.
                                    </p>
                                    <form method="POST" action="<?php echo e(route('inspections.public.approve', $inspection->public_token)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-green-900 mb-1">
                                                Comentário (opcional)
                                            </label>
                                            <textarea name="client_comment" rows="2" class="w-full border border-green-200 rounded-md px-3 py-2 text-sm" placeholder="Você pode deixar um breve comentário, se desejar."></textarea>
                                        </div>
                                        <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-green-700">
                                            Aprovar vistoria
                                        </button>
                                    </form>
                                </div>

                                <!-- Contestar -->
                                <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                                    <h3 class="font-semibold text-red-800 mb-2">Contestar vistoria</h3>
                                    <p class="text-sm text-red-900 mb-3">
                                        Use esta opção se você não concorda com algum ponto e deseja registrar sua contestação.
                                    </p>
                                    <form method="POST" action="<?php echo e(route('inspections.public.contest', $inspection->public_token)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-red-900 mb-1">
                                                Descreva o que você não concorda *
                                            </label>
                                            <textarea name="client_comment" rows="3" class="w-full border border-red-200 rounded-md px-3 py-2 text-sm" required placeholder="Explique, com suas palavras, o que você considera incorreto ou precisa ser ajustado."></textarea>
                                        </div>
                                        <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-red-700">
                                            Contestar vistoria
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="text-sm text-gray-600">
                                Esta vistoria ainda está em elaboração pela nossa equipe. Assim que for concluída, você receberá um e-mail para aprovar ou contestar.
                            </p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->latitude && $inspection->longitude): ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(config('services.google.maps_key')); ?>&callback=initMap" async defer></script>
        <script>
            function initMap() {
                const location = { lat: <?php echo e($inspection->latitude); ?>, lng: <?php echo e($inspection->longitude); ?> };
                const map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 15,
                    center: location,
                });
                new google.maps.Marker({
                    position: location,
                    map: map,
                });
            }
        </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</body>
</html>



<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/inspections/public.blade.php ENDPATH**/ ?>