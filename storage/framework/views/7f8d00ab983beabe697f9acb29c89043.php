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
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Categorias de Serviços')); ?>

            </h2>
            <a href="<?php echo e(route('service-categories.create')); ?>" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                <i class="bi bi-plus-circle mr-2"></i>
                Nova Categoria
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="p-4">
        <?php if(session('success')): ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serviços</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($category->name); ?></div>
                                <?php if($category->description): ?>
                                    <div class="text-sm text-gray-500"><?php echo e(Str::limit($category->description, 50)); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="inline-block w-6 h-6 rounded-full border border-gray-300" 
                                          style="background-color: <?php echo e($category->color); ?>"></span>
                                    <span class="ml-2 text-sm text-gray-600"><?php echo e($category->color); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($category->services_count); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full <?php echo e($category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                    <?php echo e($category->is_active ? 'Ativa' : 'Inativa'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('service-categories.show', $category)); ?>" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('service-categories.edit', $category)); ?>" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('service-categories.destroy', $category)); ?>" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                <div class="py-8">
                                    <i class="bi bi-folder-x text-4xl text-gray-400 mb-2"></i>
                                    <p class="text-lg font-medium">Nenhuma categoria encontrada</p>
                                    <p class="text-sm">Comece criando sua primeira categoria de serviço.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if($categories->hasPages()): ?>
                <div class="px-6 py-4 border-t border-gray-200">
                    <?php echo e($categories->links()); ?>

                </div>
            <?php endif; ?>
        </div>

        <!-- Quick Links -->
        <div class="mt-6 flex space-x-4">
            <a href="<?php echo e(route('services.index')); ?>" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="bi bi-tools mr-2"></i>
                Gerenciar Serviços
            </a>
            <a href="<?php echo e(route('labor-types.index')); ?>" 
               class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                <i class="bi bi-people mr-2"></i>
                Tipos de Mão de Obra
            </a>
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


<?php /**PATH C:\Users\drdes\OneDrive\Documentos\Projetos\2025\Novembro\construcaxa\resources\views/service-categories/index.blade.php ENDPATH**/ ?>