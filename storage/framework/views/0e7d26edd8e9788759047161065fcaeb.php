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
            <?php echo e(__('Configurações do Sistema')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <?php if(session('success')): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Google Maps API</h3>
                            
                            <div class="mb-4">
                                <label for="google_maps_api_key" class="block text-sm font-medium text-gray-700 mb-2">
                                    Chave da API do Google Maps
                                </label>
                                <input 
                                    type="text" 
                                    name="google_maps_api_key" 
                                    id="google_maps_api_key"
                                    value="<?php echo e(old('google_maps_api_key', $settings['google_maps_api_key'])); ?>"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="AIzaSyBNLrJhOMz6idD05pzfn5lhA-TAw-mAZCU"
                                >
                                <?php $__errorArgs = ['google_maps_api_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <p class="mt-1 text-sm text-gray-500">
                                    Chave necessária para exibir mapas no dashboard. 
                                    <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" 
                                       target="_blank" 
                                       class="text-indigo-600 hover:text-indigo-800">
                                        Obter chave da API
                                    </a>
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button 
                                type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            >
                                Salvar Configurações
                            </button>
                        </div>
                    </form>
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





<?php /**PATH C:\Users\drdes\OneDrive\Documentos\Projetos\2025\Novembro\construcaxa\resources\views/admin/settings.blade.php ENDPATH**/ ?>