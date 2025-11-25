<?php
use Illuminate\Support\Facades\Storage;
?>
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
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Perfil do Funcionário')); ?>

            </h2>
            <div class="flex gap-2">
                <a href="<?php echo e(route('attendance.employee.report', $employee)); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <?php echo e(__('Ver Pontos')); ?>

                </a>
                <a href="<?php echo e(route('employees.edit', $employee)); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <?php echo e(__('Editar')); ?>

                </a>
                <a href="<?php echo e(route('employees.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                    <?php echo e(__('Voltar')); ?>

                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Coluna Esquerda - Informações do Funcionário -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Card de Informações Básicas -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4"><?php echo e(__('Informações do Funcionário')); ?></h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Nome')); ?></label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100"><?php echo e($employee->user->name); ?></p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Email')); ?></label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100"><?php echo e($employee->user->email); ?></p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Cargo')); ?></label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100"><?php echo e($employee->position); ?></p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Departamento')); ?></label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100"><?php echo e($employee->department); ?></p>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee->phone): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Telefone')); ?></label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100"><?php echo e($employee->phone); ?></p>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee->cpf): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('CPF')); ?></label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100"><?php echo e($employee->cpf); ?></p>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee->rg): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('RG')); ?></label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100"><?php echo e($employee->rg); ?></p>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee->cnpj): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('CNPJ (MEI)')); ?></label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100"><?php echo e($employee->cnpj); ?></p>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee->hire_date): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Data de Contratação')); ?></label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100"><?php echo e($employee->hire_date->format('d/m/Y')); ?></p>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee->address): ?>
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Endereço')); ?></label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100"><?php echo e($employee->address); ?></p>
                            </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee->notes): ?>
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Observações')); ?></label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100"><?php echo e($employee->notes); ?></p>
                            </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Coluna Direita - Foto de Perfil e Fotos -->
                <div class="space-y-6">
                    <!-- Foto de Perfil -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee->profile_photo_path): ?>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4"><?php echo e(__('Foto de Perfil')); ?></h3>
                            <div class="flex justify-center">
                                <img src="<?php echo e(Storage::url($employee->profile_photo_path)); ?>" alt="<?php echo e($employee->user->name); ?>" class="h-48 w-48 rounded-full object-cover border-4 border-gray-200 dark:border-gray-600">
                            </div>
                        </div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Card de Fotos -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100"><?php echo e(__('Fotos do Funcionário')); ?></h3>
                                <button type="button" onclick="document.getElementById('photo-upload').click()" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 dark:bg-indigo-700 text-white text-xs font-medium rounded hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Adicionar
                                </button>
                            </div>

                            <form id="photo-upload-form" action="<?php echo e(route('employees.photos.store', $employee)); ?>" method="POST" enctype="multipart/form-data" class="hidden">
                                <?php echo csrf_field(); ?>
                                <input type="file" id="photo-upload" name="photo" accept="image/*" onchange="this.form.submit()">
                            </form>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee->photos && count($employee->photos) > 0): ?>
                            <div class="grid grid-cols-2 gap-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $employee->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="relative group">
                                    <img src="<?php echo e(Storage::url($photo)); ?>" alt="Foto <?php echo e($index + 1); ?>" class="w-full h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-opacity rounded-lg flex items-center justify-center">
                                        <button type="button" onclick="deletePhoto(<?php echo e($index); ?>)" class="opacity-0 group-hover:opacity-100 bg-red-600 dark:bg-red-700 text-white px-3 py-1 rounded text-sm hover:bg-red-700 dark:hover:bg-red-600 transition-colors">
                                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <?php else: ?>
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8"><?php echo e(__('Nenhuma foto adicionada ainda.')); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="delete-photo-form" method="POST" class="hidden">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
        <input type="hidden" name="photo_index" id="photo-index">
    </form>

    <script>
        function deletePhoto(index) {
            if (confirm('Tem certeza que deseja excluir esta foto?')) {
                document.getElementById('photo-index').value = index;
                document.getElementById('delete-photo-form').action = '<?php echo e(route("employees.photos.destroy", $employee)); ?>';
                document.getElementById('delete-photo-form').submit();
            }
        }
    </script>
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

<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/employees/show.blade.php ENDPATH**/ ?>