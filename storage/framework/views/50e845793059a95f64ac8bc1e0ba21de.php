<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'model' => 'featured_photo',
    'label' => 'Foto',
    'deleteAction' => 'confirmDeletePhoto',
    'existingPhotoPath' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'model' => 'featured_photo',
    'label' => 'Foto',
    'deleteAction' => 'confirmDeletePhoto',
    'existingPhotoPath' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    // Obter o valor do wire:model dinamicamente
    $wireModelValue = $attributes->whereStartsWith('wire:model')->first();
    $modelName = $wireModelValue ? trim(str_replace(['wire:model=', '"', "'"], '', $wireModelValue)) : $model;
?>

<div 
    x-data="{
        isDragging: false,
        handleDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
            this.isDragging = true;
        },
        handleDragLeave(e) {
            e.preventDefault();
            e.stopPropagation();
            this.isDragging = false;
        },
        handleDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            this.isDragging = false;
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'image/avif'];
                if (validTypes.includes(file.type) || file.type.startsWith('image/')) {
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').upload('<?php echo e($modelName); ?>', file);
                }
            }
        }
    }"
>
    <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['for' => ''.e($modelName).'','value' => ''.e($label).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => ''.e($modelName).'','value' => ''.e($label).'']); ?>
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
    <div class="mt-1 relative" style="width: 200px; height: 200px;">
        <!-- Área de Drop -->
        <label 
            for="<?php echo e($modelName); ?>"
            @dragover.prevent="handleDragOver"
            @dragleave.prevent="handleDragLeave"
            @drop.prevent="handleDrop"
            :class="{ 'border-indigo-500 bg-indigo-50': isDragging, 'border-gray-300 bg-gray-50': !isDragging }"
            class="w-full h-full border-2 border-dashed rounded-lg overflow-hidden flex items-center justify-center cursor-pointer transition-all duration-200 hover:border-indigo-400 hover:bg-indigo-50/50 relative"
        >
            <!--[if BLOCK]><![endif]--><?php if($attributes->whereStartsWith('wire:model')->first()): ?>
                <?php
                    // Acessar propriedades do Livewire via @entangle ou diretamente
                    $tempPhoto = null;
                    $photoPath = $existingPhotoPath;
                    
                    // Tentar acessar via $this se disponível (contexto Livewire)
                    try {
                        if (isset($this)) {
                            $tempPhoto = $this->$modelName ?? null;
                            $pathProperty = $modelName . '_path';
                            $photoPath = $this->$pathProperty ?? $existingPhotoPath;
                        }
                    } catch (\Exception $e) {
                        // Se não conseguir acessar, usar apenas o existingPhotoPath
                    }
                ?>
                
                <!--[if BLOCK]><![endif]--><?php if($tempPhoto): ?>
                    <div class="relative w-full h-full group">
                        <img src="<?php echo e($tempPhoto->temporaryUrl()); ?>" alt="Preview" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center">
                            <span class="text-white opacity-0 group-hover:opacity-100 text-sm font-medium">Clique para trocar</span>
                        </div>
                        <!-- Loading overlay centralizado sobre a foto -->
                        <div wire:loading wire:target="<?php echo e($modelName); ?>" class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center z-10">
                            <div class="flex items-center justify-center">
                                <svg class="animate-spin h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                <?php elseif($photoPath): ?>
                    <div class="relative w-full h-full group">
                        <img src="<?php echo e(asset('storage/' . $photoPath)); ?>" alt="Foto" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center">
                            <span class="text-white opacity-0 group-hover:opacity-100 text-sm font-medium">Clique para trocar</span>
                        </div>
                        <!-- Loading overlay centralizado sobre a foto existente -->
                        <div wire:loading wire:target="<?php echo e($modelName); ?>" class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center z-10">
                            <div class="flex items-center justify-center">
                                <svg class="animate-spin h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Estado vazio com loading centralizado -->
                    <div class="absolute inset-0 w-full h-full">
                        <div wire:loading.remove wire:target="<?php echo e($modelName); ?>" class="absolute inset-0 flex flex-col items-center justify-center text-center text-gray-400 p-4">
                            <svg class="h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="text-sm font-medium text-gray-600 mb-1">Arraste e solte</p>
                            <p class="text-xs text-gray-500">ou clique para selecionar</p>
                            <p class="text-xs text-gray-400 mt-2">JPEG, PNG, JPG, GIF, WEBP, AVIF até 2MB</p>
                        </div>
                        <!-- Loading centralizado quando não há foto -->
                        <div wire:loading wire:target="<?php echo e($modelName); ?>" class="absolute inset-0 flex items-center justify-center">
                            <svg class="animate-spin h-10 w-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </label>
        
        <!-- Input de arquivo oculto -->
        <input 
            type="file" 
            id="<?php echo e($modelName); ?>" 
            wire:model="<?php echo e($modelName); ?>" 
            accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/avif"
            class="hidden"
        />
        
        <!-- Botão de delete - usando wire:key para reatividade -->
        <div wire:key="delete-button-<?php echo e($modelName); ?>">
            <!--[if BLOCK]><![endif]--><?php if(($attributes->whereStartsWith('wire:model')->first() && (isset($this) && (($this->$modelName ?? null) || ($this->{$modelName . '_path'} ?? null) || $existingPhotoPath))) || $existingPhotoPath): ?>
                <button 
                    type="button"
                    wire:click="<?php echo e($deleteAction); ?>"
                    class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-full p-2 shadow-lg transition-all duration-200 hover:scale-110 z-20"
                    title="Excluir foto"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = [$modelName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-red-500 text-xs mt-1 absolute -bottom-6 left-0"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div>
<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/components/photo-upload.blade.php ENDPATH**/ ?>