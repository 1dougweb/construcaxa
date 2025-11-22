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
                <?php echo e(__('Detalhes do Cliente')); ?>

            </h2>
            <div class="flex space-x-2">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit clients')): ?>
                <a href="<?php echo e(route('clients.edit', $client)); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    <i class="bi bi-pencil mr-2"></i>
                    Editar
                </a>
                <?php endif; ?>
                <a href="<?php echo e(route('clients.index')); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="p-4">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h1 class="text-2xl font-bold text-gray-900"><?php echo e($client->name); ?></h1>
                            <span class="px-3 py-1 text-sm rounded-full <?php echo e($client->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e($client->is_active ? 'Ativo' : 'Inativo'); ?>

                            </span>
                        </div>
                        <?php if($client->trading_name): ?>
                            <p class="text-gray-600"><?php echo e($client->trading_name); ?></p>
                        <?php endif; ?>
                        <p class="text-sm text-gray-500"><?php echo e($client->type_label); ?></p>
                    </div>
                </div>

                <!-- Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-1">CPF/CNPJ</div>
                        <div class="font-medium text-gray-900">
                            <?php if($client->cpf): ?>
                                <?php echo e($client->formatted_cpf); ?>

                            <?php elseif($client->cnpj): ?>
                                <?php echo e($client->formatted_cnpj); ?>

                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-1">Email</div>
                        <div class="font-medium text-gray-900"><?php echo e($client->email); ?></div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-1">Telefone</div>
                        <div class="font-medium text-gray-900"><?php echo e($client->phone ?: '-'); ?></div>
                    </div>
                </div>

                <!-- Endereço -->
                <?php if($client->address || $client->city): ?>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Endereço</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700">
                            <?php if($client->address): ?>
                                <?php echo e($client->address); ?>

                                <?php if($client->address_number): ?>, <?php echo e($client->address_number); ?><?php endif; ?>
                                <?php if($client->address_complement): ?> - <?php echo e($client->address_complement); ?><?php endif; ?>
                                <br>
                            <?php endif; ?>
                            <?php if($client->neighborhood): ?>
                                <?php echo e($client->neighborhood); ?>

                                <br>
                            <?php endif; ?>
                            <?php if($client->city || $client->state): ?>
                                <?php echo e($client->city); ?><?php echo e($client->state ? ' - ' . $client->state : ''); ?>

                                <?php if($client->zip_code): ?> - <?php echo e($client->zip_code); ?><?php endif; ?>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Observações -->
                <?php if($client->notes): ?>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Observações</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 whitespace-pre-wrap"><?php echo e($client->notes); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Estatísticas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="text-sm text-blue-600 mb-1">Projetos</div>
                        <div class="text-2xl font-bold text-blue-900"><?php echo e($client->projects_count); ?></div>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4">
                        <div class="text-sm text-purple-600 mb-1">Contratos</div>
                        <div class="text-2xl font-bold text-purple-900"><?php echo e($client->contracts_count); ?></div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="text-sm text-green-600 mb-1">Orçamentos</div>
                        <div class="text-2xl font-bold text-green-900"><?php echo e($client->budgets_count); ?></div>
                    </div>
                </div>

                <!-- Contratos -->
                <div class="border-t border-gray-200 pt-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Contratos</h3>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create contracts')): ?>
                        <a href="<?php echo e(route('contracts.create', ['client_id' => $client->id])); ?>" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            <i class="bi bi-plus-circle mr-2"></i>
                            Novo Contrato
                        </a>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($client->contracts->count() > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php $__currentLoopData = $client->contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h4 class="font-semibold text-gray-900"><?php echo e($contract->contract_number); ?></h4>
                                            <p class="text-sm text-gray-600"><?php echo e($contract->title); ?></p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo e($contract->status_color); ?>">
                                            <?php echo e($contract->status_label); ?>

                                        </span>
                                    </div>
                                    <?php if($contract->value): ?>
                                        <p class="text-sm text-gray-700 mb-2"><?php echo e($contract->formatted_value); ?></p>
                                    <?php endif; ?>
                                    <div class="flex space-x-2">
                                        <a href="<?php echo e(route('contracts.show', $contract)); ?>" 
                                           class="text-sm text-indigo-600 hover:text-indigo-900">
                                            Ver detalhes →
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <i class="bi bi-file-earmark-text text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-600">Nenhum contrato cadastrado para este cliente.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Projetos -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Projetos</h3>
                    </div>
                    
                    <?php if($client->projects->count() > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php $__currentLoopData = $client->projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h4 class="font-semibold text-gray-900"><?php echo e($project->name); ?></h4>
                                            <p class="text-sm text-gray-600"><?php echo e($project->code); ?></p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $project->status))); ?>

                                        </span>
                                    </div>
                                    <?php if($project->progress_percentage !== null): ?>
                                        <div class="mt-2">
                                            <div class="flex justify-between text-sm mb-1">
                                                <span>Progresso</span>
                                                <span><?php echo e($project->progress_percentage); ?>%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-indigo-600 h-2 rounded-full" style="width: <?php echo e($project->progress_percentage); ?>%"></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="mt-3">
                                        <a href="<?php echo e(route('projects.show', $project)); ?>" 
                                           class="text-sm text-indigo-600 hover:text-indigo-900">
                                            Ver projeto →
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <i class="bi bi-folder text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-600">Nenhum projeto cadastrado para este cliente.</p>
                        </div>
                    <?php endif; ?>
                </div>
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



<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/clients/show.blade.php ENDPATH**/ ?>