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
    <div class="p-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <?php echo e(__('Gestão de Pontos')); ?>

                </h2>
                <a href="<?php echo e(route('attendance.export', request()->query())); ?>" class="inline-flex items-center px-3 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md text-sm hover:bg-indigo-700 dark:hover:bg-indigo-600">
                    <?php echo e(__('Exportar CSV')); ?>

                </a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                        <div>
                            <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['for' => 'employee','value' => ''.e(__('Funcionário (ID)')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'employee','value' => ''.e(__('Funcionário (ID)')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $attributes = $__attributesOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__attributesOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $component = $__componentOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__componentOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['id' => 'employee','type' => 'number','name' => 'employee','class' => 'mt-1 block w-full','value' => ''.e($filters['employee']).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'employee','type' => 'number','name' => 'employee','class' => 'mt-1 block w-full','value' => ''.e($filters['employee']).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['for' => 'type','value' => ''.e(__('Tipo')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'type','value' => ''.e(__('Tipo')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $attributes = $__attributesOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__attributesOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $component = $__componentOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__componentOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
                            <select id="type" name="type" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm">
                                <option value="">--</option>
                                <option value="entry" <?php if($filters['type']==='entry'): echo 'selected'; endif; ?>>Entrada</option>
                                <option value="exit" <?php if($filters['type']==='exit'): echo 'selected'; endif; ?>>Saída</option>
                            </select>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['for' => 'from','value' => ''.e(__('De (data)')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'from','value' => ''.e(__('De (data)')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $attributes = $__attributesOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__attributesOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $component = $__componentOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__componentOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['id' => 'from','type' => 'date','name' => 'from','class' => 'mt-1 block w-full','value' => ''.e($filters['from']).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'from','type' => 'date','name' => 'from','class' => 'mt-1 block w-full','value' => ''.e($filters['from']).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['for' => 'to','value' => ''.e(__('Até (data)')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'to','value' => ''.e(__('Até (data)')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $attributes = $__attributesOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__attributesOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $component = $__componentOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__componentOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['id' => 'to','type' => 'date','name' => 'to','class' => 'mt-1 block w-full','value' => ''.e($filters['to']).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'to','type' => 'date','name' => 'to','class' => 'mt-1 block w-full','value' => ''.e($filters['to']).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                        </div>
                        <div class="flex items-end">
                            <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','class' => 'w-full']); ?><?php echo e(__('Filtrar')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Data')); ?></th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Hora')); ?></th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Tipo')); ?></th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Funcionário')); ?></th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Localização')); ?></th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Precisão')); ?></th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Ações')); ?></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $employee = \App\Models\Employee::where('user_id', $row->user_id)->first();
                                    ?>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-3 py-2 text-gray-900 dark:text-gray-100"><?php echo e(optional($row->punched_date)->format('Y-m-d')); ?></td>
                                        <td class="px-3 py-2 text-gray-900 dark:text-gray-100"><?php echo e(optional($row->punched_at)->format('H:i')); ?></td>
                                        <td class="px-3 py-2">
                                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium <?php echo e($row->type==='entry' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300'); ?>">
                                                <?php echo e($row->type==='entry' ? 'Entrada' : 'Saída'); ?>

                                            </span>
                                        </td>
                                        <td class="px-3 py-2 text-gray-900 dark:text-gray-100"><?php echo e(optional($row->user)->name); ?> (ID <?php echo e($row->user_id); ?>)</td>
                                        <td class="px-3 py-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($row->latitude && $row->longitude): ?>
                                                <button 
                                                    type="button"
                                                    @click="window.openMapModal(<?php echo e($row->latitude); ?>, <?php echo e($row->longitude); ?>, '<?php echo e($row->id); ?>', '<?php echo e(optional($row->punched_date)->format('d/m/Y')); ?>', '<?php echo e(optional($row->punched_at)->format('H:i')); ?>', '<?php echo e($row->type === 'entry' ? 'Entrada' : 'Saída'); ?>', '<?php echo e(optional($row->user)->name); ?>', <?php echo e($row->accuracy ?? 'null'); ?>)"
                                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline text-sm font-medium">
                                                    Ver no Mapa
                                                </button>
                                            <?php else: ?>
                                                <span class="text-gray-400 dark:text-gray-500 text-sm">N/A</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td class="px-3 py-2 text-gray-900 dark:text-gray-100"><?php echo e($row->accuracy ? number_format($row->accuracy, 1) . ' m' : '-'); ?></td>
                                        <td class="px-3 py-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee): ?>
                                                <a href="<?php echo e(route('employees.show', $employee)); ?>" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline text-sm font-medium">
                                                    Ver Perfil
                                                </a>
                                            <?php else: ?>
                                                <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="px-3 py-6 text-center text-gray-500 dark:text-gray-400"><?php echo e(__('Sem registros')); ?></td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4"><?php echo e($attendances->links()); ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Localização com Google Maps -->
    <div id="location-map-modal" 
         x-data="{ 
             open: false, 
             lat: null, 
             lng: null, 
             attendanceId: null,
             date: null,
             time: null,
             type: null,
             employee: null,
             accuracy: null,
             map: null,
             marker: null,
             address: null
         }"
         x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background-color: rgba(0, 0, 0, 0.75); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); display: none;"
         @click.self="open = false; closeMap()"
         @keydown.escape.window="open = false; closeMap()">
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white dark:bg-gray-800 rounded-lg shadow-2xl p-6 w-full max-w-4xl mx-4 border border-gray-200 dark:border-gray-700"
             style="max-height: calc(100vh - 3rem); overflow-y: auto;"
             @click.stop>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-lg">Localização do Ponto</h3>
                    <div class="mt-1 text-sm text-gray-600 dark:text-gray-400" x-show="date && time && type && employee">
                        <span x-text="date"></span> às <span x-text="time"></span> • 
                        <span x-text="type"></span> • 
                        <span x-text="employee"></span>
                        <span x-show="accuracy" x-text="' • Precisão: ' + accuracy + ' m'"></span>
                    </div>
                </div>
                <button @click="open = false; closeMap()" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 transition-colors p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div x-show="address" class="mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-md border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-700 dark:text-gray-300"><strong class="text-gray-900 dark:text-gray-100">Endereço:</strong> <span x-text="address || 'Carregando...'"></span></p>
            </div>

            <div id="map-container" class="w-full h-96 rounded-md border border-gray-300 dark:border-gray-600" style="min-height: 384px;"></div>
        </div>
    </div>

    <script>
        // Definir stub inicial para garantir que a função esteja disponível para Alpine
        // Isso garante que a função exista antes do Alpine tentar usá-la
        (function() {
            window.openMapModal = window.openMapModal || function() {
                console.warn('openMapModal: Google Maps não está configurado ainda');
            };
            window.closeMap = window.closeMap || function() {};
        })();
    </script>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($googleMapsApiKey): ?>
    <script>
        let mapInstance = null;
        let markerInstance = null;
        let currentModalData = null;
        let mapObserver = null;

        window.openMapModal = function(lat, lng, attendanceId, date, time, type, employee, accuracy) {
            const modal = document.getElementById('location-map-modal');
            if (!modal) return;
            
            // Usar evento customizado para atualizar dados do Alpine
            const event = new CustomEvent('open-map-modal', {
                detail: {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng),
                    attendanceId: attendanceId,
                    date: date,
                    time: time,
                    type: type,
                    employee: employee,
                    accuracy: accuracy ? parseFloat(accuracy).toFixed(1) : null
                }
            });
            modal.dispatchEvent(event);
            
            // Acessar dados do Alpine via $dispatch ou diretamente
            if (typeof Alpine !== 'undefined') {
                const alpineData = Alpine.$data(modal);
                if (alpineData) {
                    alpineData.lat = parseFloat(lat);
                    alpineData.lng = parseFloat(lng);
                    alpineData.attendanceId = attendanceId;
                    alpineData.date = date;
                    alpineData.time = time;
                    alpineData.type = type;
                    alpineData.employee = employee;
                    alpineData.accuracy = accuracy ? parseFloat(accuracy).toFixed(1) : null;
                    alpineData.open = true;
                    
                    // Carregar endereço via reverse geocoding
                    fetch(`<?php echo e(route('attendance.reverse-geocode')); ?>?lat=${lat}&lng=${lng}`)
                        .then(r => r.json())
                        .then(d => {
                            alpineData.address = d.address || 'Endereço não disponível';
                        })
                        .catch(() => {
                            alpineData.address = 'Erro ao obter endereço';
                        });
                }
            }
            
            modal.style.display = 'flex';
            currentModalData = { lat: parseFloat(lat), lng: parseFloat(lng), accuracy: accuracy ? parseFloat(accuracy) : null };
            
            // Inicializar mapa após um pequeno delay para garantir que o modal está visível
            setTimeout(() => {
                initMap(parseFloat(lat), parseFloat(lng), accuracy ? parseFloat(accuracy) : null);
            }, 100);
        };

        window.closeMap = function() {
            const modal = document.getElementById('location-map-modal');
            if (!modal) return;
            
            if (typeof Alpine !== 'undefined') {
                const alpineData = Alpine.$data(modal);
                if (alpineData) {
                    alpineData.open = false;
                }
            }
            
            if (mapInstance) {
                // Limpar mapa
                if (markerInstance) {
                    markerInstance.setMap(null);
                    markerInstance = null;
                }
                mapInstance = null;
            }
            
            if (mapObserver) {
                mapObserver.disconnect();
                mapObserver = null;
            }
            
            modal.style.display = 'none';
        };

        function initMap(lat, lng, accuracy) {
            const container = document.getElementById('map-container');
            if (!container) return;

            // Verificar se Google Maps está carregado
            if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                // Carregar Google Maps API
                const script = document.createElement('script');
                script.src = `https://maps.googleapis.com/maps/api/js?key=<?php echo e($googleMapsApiKey); ?>&libraries=geometry`;
                script.async = true;
                script.defer = true;
                script.onload = () => createMap(lat, lng, accuracy);
                document.head.appendChild(script);
            } else {
                createMap(lat, lng, accuracy);
            }
        }

        function createMap(lat, lng, accuracy) {
            const container = document.getElementById('map-container');
            if (!container) return;
            
            // Limpar instância anterior se existir
            if (mapInstance) {
                if (markerInstance) {
                    markerInstance.setMap(null);
                    markerInstance = null;
                }
                mapInstance = null;
            }

            const position = { lat: parseFloat(lat), lng: parseFloat(lng) };
            
            // Verificar se está em dark mode
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            // Estilos do mapa para dark mode
            const darkMapStyles = [
                {elementType: 'geometry', stylers: [{color: '#242f3e'}]},
                {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
                {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
                {
                    featureType: 'administrative.locality',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563'}]
                },
                {
                    featureType: 'poi',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563'}]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'geometry',
                    stylers: [{color: '#263c3f'}]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#6b9a76'}]
                },
                {
                    featureType: 'road',
                    elementType: 'geometry',
                    stylers: [{color: '#38414e'}]
                },
                {
                    featureType: 'road',
                    elementType: 'geometry.stroke',
                    stylers: [{color: '#212a37'}]
                },
                {
                    featureType: 'road',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#9ca5b3'}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry',
                    stylers: [{color: '#746855'}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry.stroke',
                    stylers: [{color: '#1f2835'}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#f3d19c'}]
                },
                {
                    featureType: 'transit',
                    elementType: 'geometry',
                    stylers: [{color: '#2f3948'}]
                },
                {
                    featureType: 'transit.station',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563'}]
                },
                {
                    featureType: 'water',
                    elementType: 'geometry',
                    stylers: [{color: '#17263c'}]
                },
                {
                    featureType: 'water',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#515c6d'}]
                },
                {
                    featureType: 'water',
                    elementType: 'labels.text.stroke',
                    stylers: [{color: '#17263c'}]
                }
            ];

            mapInstance = new google.maps.Map(container, {
                zoom: 17,
                center: position,
                mapTypeId: 'roadmap',
                styles: isDarkMode ? darkMapStyles : [],
            });

            // Adicionar marcador
            markerInstance = new google.maps.Marker({
                position: position,
                map: mapInstance,
                title: 'Localização do Ponto',
                animation: google.maps.Animation.DROP,
            });

            // Adicionar círculo de precisão se disponível
            if (accuracy && accuracy > 0) {
                const circle = new google.maps.Circle({
                    strokeColor: '#3B82F6',
                    strokeOpacity: 0.3,
                    strokeWeight: 2,
                    fillColor: '#3B82F6',
                    fillOpacity: 0.1,
                    map: mapInstance,
                    center: position,
                    radius: accuracy,
                });
            }

            // Info window com informações (reutilizando isDarkMode já declarado acima)
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div class="p-2 ${isDarkMode ? 'dark' : ''}">
                        <p class="font-semibold text-gray-900 dark:text-gray-100">Localização do Ponto</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Lat: ${position.lat.toFixed(5)}, Lng: ${position.lng.toFixed(5)}</p>
                        ${accuracy ? `<p class="text-sm text-gray-600 dark:text-gray-400">Precisão: ${accuracy.toFixed(1)} m</p>` : ''}
                    </div>
                `
            });

            markerInstance.addListener('click', () => {
                infoWindow.open(mapInstance, markerInstance);
            });

            // Forçar redimensionamento
            setTimeout(() => {
                google.maps.event.trigger(mapInstance, 'resize');
                mapInstance.setCenter(position);
            }, 100);
            
            // Observar mudanças no tema
            if (mapObserver) {
                mapObserver.disconnect();
            }
            
            mapObserver = new MutationObserver(() => {
                const currentDarkMode = document.documentElement.classList.contains('dark');
                if (mapInstance) {
                    mapInstance.setOptions({
                        styles: currentDarkMode ? darkMapStyles : []
                    });
                }
            });
            
            mapObserver.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
        }
    </script>
    <?php else: ?>
    <script>
        window.openMapModal = function(lat, lng, attendanceId, date, time, type, employee, accuracy) {
            alert('Google Maps não está configurado. Configure a chave da API nas configurações do sistema.');
        };
        window.closeMap = function() {};
    </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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


<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/attendance/manage.blade.php ENDPATH**/ ?>