<?php if (isset($component)) { $__componentOriginal4619374cef299e94fd7263111d0abc69 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4619374cef299e94fd7263111d0abc69 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Tipos de Mão de Obra')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="p-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Gerenciar Tipos de Mão de Obra</h1>
            <a href="<?php echo e(route('labor-types.create')); ?>" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                <i class="bi bi-plus-circle mr-2"></i>
                Novo Tipo
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                           placeholder="Nome do tipo..." 
                           class="w-full border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nível</label>
                    <select name="skill_level" class="w-full border-gray-300 rounded-md">
                        <option value="">Todos os níveis</option>
                        <?php $__currentLoopData = $skillLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php echo e(request('skill_level') == $value ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border-gray-300 rounded-md">
                        <option value="">Todos</option>
                        <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Ativo</option>
                        <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inativo</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Labor Types Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $laborTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laborType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white rounded-lg shadow-md border hover:shadow-lg transition-shadow duration-200">
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg text-gray-900 mb-1"><?php echo e($laborType->name); ?></h3>
                                <span class="px-2 py-1 text-xs rounded-full <?php echo e($laborType->skill_level_color); ?>">
                                    <?php echo e($laborType->skill_level_label); ?>

                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs rounded-full <?php echo e($laborType->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                    <?php echo e($laborType->is_active ? 'Ativo' : 'Inativo'); ?>

                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        <?php if($laborType->description): ?>
                            <p class="text-sm text-gray-600 mb-4"><?php echo e(Str::limit($laborType->description, 100)); ?></p>
                        <?php endif; ?>

                        <!-- Pricing Info -->
                        <div class="bg-gray-50 rounded-lg p-3 mb-4 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Hora Normal:</span>
                                <span class="font-medium text-purple-600"><?php echo e($laborType->formatted_hourly_rate); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Hora Extra:</span>
                                <span class="font-medium text-orange-600"><?php echo e($laborType->formatted_overtime_rate); ?></span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="<?php echo e(route('labor-types.edit', $laborType)); ?>" 
                               class="flex-1 text-center px-3 py-2 text-sm bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                                Editar
                            </a>
                            <a href="<?php echo e(route('labor-types.show', $laborType)); ?>" 
                               class="px-3 py-2 text-sm bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                                Ver
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">
                            <i class="bi bi-people" style="font-size: 4rem;"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum tipo de mão de obra encontrado</h3>
                        <p class="text-gray-600 mb-4">Comece criando seu primeiro tipo de mão de obra.</p>
                        <a href="<?php echo e(route('labor-types.create')); ?>" 
                           class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                            <i class="bi bi-plus-circle mr-2"></i>
                            Criar Tipo
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if($laborTypes->hasPages()): ?>
            <div class="mt-6"><?php echo e($laborTypes->links()); ?></div>
        <?php endif; ?>

        <!-- Quick Links -->
        <div class="mt-8 border-t pt-6">
            <div class="flex space-x-4">
                <a href="<?php echo e(route('services.index')); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    <i class="bi bi-tools mr-2"></i>
                    Gerenciar Serviços
                </a>
                <a href="<?php echo e(route('service-categories.index')); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    <i class="bi bi-folder mr-2"></i>
                    Categorias de Serviços
                </a>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php /**PATH C:\Users\drdes\OneDrive\Documentos\Projetos\2025\Novembro\construcaxa\resources\views/labor-types/index.blade.php ENDPATH**/ ?>