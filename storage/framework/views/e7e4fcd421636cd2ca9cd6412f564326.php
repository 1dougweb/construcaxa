<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['label' => 'Fotos do Orçamento']));

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

foreach (array_filter((['label' => 'Fotos do Orçamento']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="space-y-3">
    <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['value' => $label]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($label)]); ?>
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
    
    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
        <div class="grid grid-cols-3 md:grid-cols-5 gap-3">
            <!-- Existing Photos -->
            <!--[if BLOCK]><![endif]--><?php if(isset($photos) && is_array($photos) && count($photos) > 0): ?>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="relative group aspect-video bg-white rounded border border-gray-300 overflow-hidden">
                        <img src="<?php echo e(asset('storage/' . $photo)); ?>" alt="Foto" class="w-full h-full object-cover">
                        <button 
                            type="button"
                            wire:click="confirmDeletePhoto(<?php echo e($index); ?>)"
                            class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity z-10 shadow-lg"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            <!-- Temp Photos (Uploading) -->
            <!--[if BLOCK]><![endif]--><?php if(isset($tempPhotos) && is_array($tempPhotos) && count($tempPhotos) > 0): ?>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $tempPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $tempPhoto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="relative aspect-video bg-white rounded border border-gray-300 overflow-hidden">
                        <!--[if BLOCK]><![endif]--><?php if($tempPhoto): ?>
                            <img src="<?php echo e($tempPhoto->temporaryUrl()); ?>" alt="Uploading..." class="w-full h-full object-cover opacity-50">
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <div class="absolute inset-0 flex items-center justify-center bg-gray-900 bg-opacity-30">
                            <svg class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            <!-- Upload Area -->
            <!--[if BLOCK]><![endif]--><?php if((isset($photos) ? count($photos) : 0) + (isset($tempPhotos) ? count($tempPhotos) : 0) < 20): ?>
                <div 
                    x-data="{
                        isDragging: false,
                        handleDrop(e) {
                            e.preventDefault();
                            this.isDragging = false;
                            const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
                            if (files.length > 0) {
                                files.forEach((file, index) => {
                                    const currentCount = <?php echo e(isset($photos) ? count($photos) : 0); ?> + <?php echo e(isset($tempPhotos) ? count($tempPhotos) : 0); ?>;
                                    if ((currentCount + index) < 20) {
                                        window.Livewire.find('<?php echo e($_instance->getId()); ?>').upload('tempPhotos.' + (<?php echo e(isset($tempPhotos) ? count($tempPhotos) : 0); ?> + index), file);
                                    }
                                });
                            }
                        },
                        handleFileSelect(e) {
                            const files = Array.from(e.target.files).filter(file => file.type.startsWith('image/'));
                            if (files.length > 0) {
                                files.forEach((file, index) => {
                                    const currentCount = <?php echo e(isset($photos) ? count($photos) : 0); ?> + <?php echo e(isset($tempPhotos) ? count($tempPhotos) : 0); ?>;
                                    if ((currentCount + index) < 20) {
                                        window.Livewire.find('<?php echo e($_instance->getId()); ?>').upload('tempPhotos.' + (<?php echo e(isset($tempPhotos) ? count($tempPhotos) : 0); ?> + index), file);
                                    }
                                });
                            }
                            e.target.value = '';
                        }
                    }"
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="handleDrop"
                    @click="$refs.fileInput.click()"
                    class="aspect-video border-2 border-dashed rounded border-gray-400 flex flex-col items-center justify-center cursor-pointer transition-colors bg-white hover:bg-gray-50"
                    :class="isDragging ? 'border-indigo-500 bg-indigo-50' : ''"
                >
                    <input 
                        type="file" 
                        x-ref="fileInput"
                        @change="handleFileSelect"
                        multiple
                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/avif"
                        class="hidden"
                    >
                    <svg class="w-8 h-8 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <p class="text-xs text-gray-500 text-center px-2">
                        Adicionar
                    </p>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        
        <p class="text-xs text-gray-500 mt-3">
            Máximo de 20 fotos. Formatos aceitos: JPG, PNG, WEBP, AVIF
        </p>
    </div>
    
    <!--[if BLOCK]><![endif]--><?php if(isset($showDeleteModal) && $showDeleteModal): ?>
        <?php if (isset($component)) { $__componentOriginal7ef94aa801410a663a471c55b223c943 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7ef94aa801410a663a471c55b223c943 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-confirm','data' => ['show' => $showDeleteModal,'title' => 'Excluir Foto','message' => 'Tem certeza que deseja excluir esta foto?','confirmText' => 'Excluir','cancelText' => 'Cancelar','type' => 'danger','confirmAction' => 'deletePhoto','cancelAction' => 'cancelDeletePhoto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-confirm'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['show' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showDeleteModal),'title' => 'Excluir Foto','message' => 'Tem certeza que deseja excluir esta foto?','confirm-text' => 'Excluir','cancel-text' => 'Cancelar','type' => 'danger','confirm-action' => 'deletePhoto','cancel-action' => 'cancelDeletePhoto']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7ef94aa801410a663a471c55b223c943)): ?>
<?php $attributes = $__attributesOriginal7ef94aa801410a663a471c55b223c943; ?>
<?php unset($__attributesOriginal7ef94aa801410a663a471c55b223c943); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7ef94aa801410a663a471c55b223c943)): ?>
<?php $component = $__componentOriginal7ef94aa801410a663a471c55b223c943; ?>
<?php unset($__componentOriginal7ef94aa801410a663a471c55b223c943); ?>
<?php endif; ?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>

<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/components/budget-photo-gallery.blade.php ENDPATH**/ ?>