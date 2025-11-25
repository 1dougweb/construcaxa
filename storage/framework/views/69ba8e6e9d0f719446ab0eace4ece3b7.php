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
                <?php echo e(__('Relatório de Pontos - ') . $employee->user->name); ?>

            </h2>
            <div class="flex gap-2">
                <a href="<?php echo e(route('attendance.employee.pdf', ['employee' => $employee, 'from' => $from, 'to' => $to])); ?>" class="inline-flex items-center px-4 py-2 bg-red-600 dark:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 dark:hover:bg-red-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <?php echo e(__('Imprimir PDF')); ?>

                </a>
                <a href="<?php echo e(route('employees.show', $employee)); ?>" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600">
                    <?php echo e(__('Voltar')); ?>

                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['id' => 'from','type' => 'date','name' => 'from','class' => 'mt-1 block w-full','value' => ''.e($from).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'from','type' => 'date','name' => 'from','class' => 'mt-1 block w-full','value' => ''.e($from).'']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['id' => 'to','type' => 'date','name' => 'to','class' => 'mt-1 block w-full','value' => ''.e($to).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'to','type' => 'date','name' => 'to','class' => 'mt-1 block w-full','value' => ''.e($to).'']); ?>
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
                        <div class="flex items-end">
                            <a href="<?php echo e(route('attendance.employee.report', $employee)); ?>" class="w-full text-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                                <?php echo e(__('Limpar')); ?>

                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resumo -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('Horas Trabalhadas')); ?></div>
                        <div class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100"><?php echo e(number_format($hoursWorked, 2, ',', '.')); ?>h</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('Valor Bruto')); ?></div>
                        <div class="mt-2 text-2xl font-semibold text-green-600 dark:text-green-400">R$ <?php echo e(number_format($grossAmount, 2, ',', '.')); ?></div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('Descontos')); ?></div>
                        <div class="mt-2 text-2xl font-semibold text-red-600 dark:text-red-400">R$ <?php echo e(number_format($totalDeductions, 2, ',', '.')); ?></div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border-2 border-indigo-500 dark:border-indigo-400">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('Valor Líquido')); ?></div>
                        <div class="mt-2 text-2xl font-semibold text-indigo-600 dark:text-indigo-400">R$ <?php echo e(number_format($netAmount, 2, ',', '.')); ?></div>
                    </div>
                </div>
            </div>

            <!-- Tabela de Pontos -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4"><?php echo e(__('Registros de Ponto')); ?></h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Data')); ?></th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Hora')); ?></th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Tipo')); ?></th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Horas do Dia')); ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                <?php
                                    $groupedByDate = $attendances->groupBy(function($attendance) {
                                        return $attendance->punched_date->format('Y-m-d');
                                    });
                                    $dayHours = [];
                                    $entryTime = null;
                                    foreach($attendances->sortBy('punched_at') as $att) {
                                        if($att->type === 'entry') {
                                            $entryTime = $att->punched_at;
                                        } elseif($att->type === 'exit' && $entryTime) {
                                            $dateKey = $att->punched_date->format('Y-m-d');
                                            $hours = $entryTime->diffInMinutes($att->punched_at) / 60;
                                            $dayHours[$dateKey] = ($dayHours[$dateKey] ?? 0) + $hours;
                                            $entryTime = null;
                                        }
                                    }
                                ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $groupedByDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $dayAttendances): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $entries = $dayAttendances->where('type', 'entry')->sortBy('punched_at');
                                        $exits = $dayAttendances->where('type', 'exit')->sortBy('punched_at');
                                        $dayTotalHours = $dayHours[$date] ?? 0;
                                    ?>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-3 py-2 text-gray-900 dark:text-gray-100"><?php echo e(\Carbon\Carbon::parse($date)->format('d/m/Y')); ?></td>
                                        <td class="px-3 py-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="text-green-600 dark:text-green-400"><?php echo e($entry->punched_at->format('H:i')); ?></span>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->last): ?>, <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($entries->count() > 0 && $exits->count() > 0): ?> <span class="mx-1 text-gray-500 dark:text-gray-400">-</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $exits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="text-indigo-600 dark:text-indigo-400"><?php echo e($exit->punched_at->format('H:i')); ?></span>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->last): ?>, <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if($entries->count() === 0 && $exits->count() === 0): ?>
                                                <span class="text-gray-400 dark:text-gray-500">-</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td class="px-3 py-2">
                                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                                <?php echo e($entries->count() > 0 ? 'Entrada' : ''); ?><?php echo e($entries->count() > 0 && $exits->count() > 0 ? ' / ' : ''); ?><?php echo e($exits->count() > 0 ? 'Saída' : ''); ?>

                                            </span>
                                        </td>
                                        <td class="px-3 py-2 font-medium text-gray-900 dark:text-gray-100">
                                            <?php echo e($dayTotalHours > 0 ? number_format($dayTotalHours, 2, ',', '.') . 'h' : '-'); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="px-3 py-6 text-center text-gray-500 dark:text-gray-400"><?php echo e(__('Sem registros no período')); ?></td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Descontos -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100"><?php echo e(__('Descontos Aplicados')); ?></h3>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit employees')): ?>
                        <button type="button" onclick="document.getElementById('deduction-modal').classList.remove('hidden')" class="inline-flex items-center px-3 py-1.5 bg-red-600 dark:bg-red-700 text-white text-xs font-medium rounded hover:bg-red-700 dark:hover:bg-red-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Adicionar Desconto
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($deductions->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Data')); ?></th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Descrição')); ?></th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Valor')); ?></th>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit employees')): ?>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300"><?php echo e(__('Ações')); ?></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $deductions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deduction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100"><?php echo e($deduction->date->format('d/m/Y')); ?></td>
                                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100"><?php echo e($deduction->description); ?></td>
                                    <td class="px-3 py-2 text-red-600 dark:text-red-400 font-medium">R$ <?php echo e(number_format($deduction->amount, 2, ',', '.')); ?></td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit employees')): ?>
                                    <td class="px-3 py-2">
                                        <form class="delete-deduction-form" method="POST" action="<?php echo e(route('employees.deductions.destroy', ['employee' => $employee, 'deduction' => $deduction])); ?>" onsubmit="return false;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <tr class="bg-gray-50 dark:bg-gray-700 font-semibold">
                                    <td colspan="<?php echo e(auth()->user()->can('edit employees') ? '3' : '2'); ?>" class="px-3 py-2 text-right text-gray-900 dark:text-gray-100"><?php echo e(__('Total de Descontos')); ?></td>
                                    <td class="px-3 py-2 text-red-600 dark:text-red-400">R$ <?php echo e(number_format($totalDeductions, 2, ',', '.')); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8"><?php echo e(__('Nenhum desconto aplicado no período.')); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Modal de Adicionar Desconto -->
            <div id="deduction-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color: rgba(0, 0, 0, 0.75);">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl p-6 w-full max-w-md mx-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Adicionar Desconto</h3>
                        <button type="button" onclick="document.getElementById('deduction-modal').classList.add('hidden')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form id="deduction-form" method="POST" action="<?php echo e(route('employees.deductions.store', $employee)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="space-y-4">
                            <div>
                                <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['for' => 'description','value' => ''.e(__('Descrição')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'description','value' => ''.e(__('Descrição')).'']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['id' => 'description','type' => 'text','class' => 'mt-1 block w-full','name' => 'description','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'description','type' => 'text','class' => 'mt-1 block w-full','name' => 'description','required' => true]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['for' => 'amount','value' => ''.e(__('Valor')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'amount','value' => ''.e(__('Valor')).'']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['id' => 'amount','type' => 'number','step' => '0.01','min' => '0.01','class' => 'mt-1 block w-full','name' => 'amount','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'amount','type' => 'number','step' => '0.01','min' => '0.01','class' => 'mt-1 block w-full','name' => 'amount','required' => true]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['for' => 'date','value' => ''.e(__('Data')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'date','value' => ''.e(__('Data')).'']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['id' => 'date','type' => 'date','class' => 'mt-1 block w-full','name' => 'date','value' => ''.e(date('Y-m-d')).'','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'date','type' => 'date','class' => 'mt-1 block w-full','name' => 'date','value' => ''.e(date('Y-m-d')).'','required' => true]); ?>
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
                            <div class="flex gap-2">
                                <?php if (isset($component)) { $__componentOriginal635944f67ec1864e436b88f435140e07 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal635944f67ec1864e436b88f435140e07 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button-loading','data' => ['variant' => 'danger','type' => 'submit','class' => 'flex-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button-loading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'danger','type' => 'submit','class' => 'flex-1']); ?>
                                    <?php echo e(__('Adicionar')); ?>

                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal635944f67ec1864e436b88f435140e07)): ?>
<?php $attributes = $__attributesOriginal635944f67ec1864e436b88f435140e07; ?>
<?php unset($__attributesOriginal635944f67ec1864e436b88f435140e07); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal635944f67ec1864e436b88f435140e07)): ?>
<?php $component = $__componentOriginal635944f67ec1864e436b88f435140e07; ?>
<?php unset($__componentOriginal635944f67ec1864e436b88f435140e07); ?>
<?php endif; ?>
                                <button type="button" id="cancel-deduction-btn" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-600">
                                    <?php echo e(__('Cancelar')); ?>

                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deductionForm = document.getElementById('deduction-form');
            const deductionModal = document.getElementById('deduction-modal');
            const cancelBtn = document.getElementById('cancel-deduction-btn');
            
            // Fechar modal ao clicar no botão cancelar
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    deductionModal.classList.add('hidden');
                    if (deductionForm) deductionForm.reset();
                });
            }
            
            // Fechar modal ao clicar no X
            const closeModalBtn = deductionModal?.querySelector('button[onclick*="deduction-modal"]');
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', function() {
                    deductionModal.classList.add('hidden');
                    if (deductionForm) deductionForm.reset();
                });
            }
            
            // Interceptar submit do formulário de adicionar desconto
            if (deductionForm) {
                deductionForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(deductionForm);
                    const submitButton = deductionForm.querySelector('[data-loading-button]');
                    const originalText = submitButton ? submitButton.querySelector('.button-text').textContent : '';
                    
                    // Ativar estado de loading
                    if (submitButton) {
                        const spinner = submitButton.querySelector('.loading-spinner');
                        const buttonText = submitButton.querySelector('.button-text');
                        if (spinner) spinner.style.display = 'inline-block';
                        if (buttonText) {
                            buttonText.textContent = 'Adicionando...';
                            buttonText.style.opacity = '0.7';
                        }
                        submitButton.disabled = true;
                    }
                    
                    try {
                        const response = await fetch(deductionForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || formData.get('_token')
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok && data.success) {
                            // Mostrar notificação de sucesso
                            if (window.showNotification) {
                                window.showNotification(data.message || 'Desconto adicionado com sucesso!', 'success');
                            }
                            
                            // Fechar modal e resetar formulário
                            deductionModal.classList.add('hidden');
                            deductionForm.reset();
                            
                            // Recarregar a página para atualizar a lista de descontos
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        } else {
                            // Mostrar notificação de erro
                            if (window.showNotification) {
                                window.showNotification(data.message || 'Erro ao adicionar desconto.', 'error');
                            }
                            
                            // Restaurar botão
                            if (submitButton) {
                                const spinner = submitButton.querySelector('.loading-spinner');
                                const buttonText = submitButton.querySelector('.button-text');
                                if (spinner) spinner.style.display = 'none';
                                if (buttonText) {
                                    buttonText.textContent = originalText;
                                    buttonText.style.opacity = '1';
                                }
                                submitButton.disabled = false;
                            }
                        }
                    } catch (error) {
                        console.error('Erro ao enviar formulário:', error);
                        
                        // Mostrar notificação de erro
                        if (window.showNotification) {
                            window.showNotification('Erro ao adicionar desconto. Tente novamente.', 'error');
                        }
                        
                        // Restaurar botão
                        if (submitButton) {
                            const spinner = submitButton.querySelector('.loading-spinner');
                            const buttonText = submitButton.querySelector('.button-text');
                            if (spinner) spinner.style.display = 'none';
                            if (buttonText) {
                                buttonText.textContent = originalText;
                                buttonText.style.opacity = '1';
                            }
                            submitButton.disabled = false;
                        }
                    }
                });
            }
            
            // Interceptar submit dos formulários de deletar desconto
            const deleteForms = document.querySelectorAll('.delete-deduction-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    if (!confirm('Tem certeza que deseja excluir este desconto?')) {
                        return;
                    }
                    
                    const formData = new FormData(form);
                    const submitButton = form.querySelector('button[type="submit"]');
                    const originalHTML = submitButton ? submitButton.innerHTML : '';
                    
                    // Ativar estado de loading
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                    
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || formData.get('_token')
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok && data.success) {
                            // Mostrar notificação de sucesso
                            if (window.showNotification) {
                                window.showNotification(data.message || 'Desconto excluído com sucesso!', 'success');
                            }
                            
                            // Recarregar a página para atualizar a lista de descontos
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        } else {
                            // Mostrar notificação de erro
                            if (window.showNotification) {
                                window.showNotification(data.message || 'Erro ao excluir desconto.', 'error');
                            }
                            
                            // Restaurar botão
                            if (submitButton) {
                                submitButton.disabled = false;
                                submitButton.innerHTML = originalHTML;
                                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                            }
                        }
                    } catch (error) {
                        console.error('Erro ao excluir desconto:', error);
                        
                        // Mostrar notificação de erro
                        if (window.showNotification) {
                            window.showNotification('Erro ao excluir desconto. Tente novamente.', 'error');
                        }
                        
                        // Restaurar botão
                        if (submitButton) {
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalHTML;
                            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    }
                });
            });
        });
    </script>
    <?php $__env->stopPush(); ?>
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

<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/attendance/employee-report.blade.php ENDPATH**/ ?>