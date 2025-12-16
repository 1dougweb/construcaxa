<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => 'document_file',
    'label' => 'Documento',
    'required' => false,
    'existingDocumentPath' => null,
    'accept' => '.pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar',
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
    'name' => 'document_file',
    'label' => 'Documento',
    'required' => false,
    'existingDocumentPath' => null,
    'accept' => '.pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $documentUrl = null;
    $documentName = null;
    if ($existingDocumentPath && $existingDocumentPath !== '' && $existingDocumentPath !== null) {
        $documentUrl = '/' . ltrim($existingDocumentPath, '/');
        $documentName = basename($existingDocumentPath);
    }
?>

<div 
    x-data="{
        preview: null,
        fileName: null,
        fileSize: null,
        isDragging: false,
        handleFileSelect(e) {
            const file = e.target.files[0] || (e.dataTransfer && e.dataTransfer.files[0]);
            if (file) {
                this.fileName = file.name;
                this.fileSize = (file.size / 1024 / 1024).toFixed(2);
                
                // Preview para imagens
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.preview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    this.preview = null;
                }
                
                // Mostrar preview do novo arquivo
                document.getElementById('new-document-<?php echo e($name); ?>').style.display = 'flex';
            }
        },
        removeNewFile() {
            this.preview = null;
            this.fileName = null;
            this.fileSize = null;
            const fileInput = document.getElementById('<?php echo e($name); ?>');
            if (fileInput) {
                fileInput.value = '';
            }
            document.getElementById('new-document-<?php echo e($name); ?>').style.display = 'none';
        },
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
            const fileInput = document.getElementById('<?php echo e($name); ?>');
            if (fileInput && e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                this.handleFileSelect({ target: fileInput });
            }
        }
    }"
    class="space-y-2"
>
    <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['for' => ''.e($name).'','value' => ''.e($label).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => ''.e($name).'','value' => ''.e($label).'']); ?>
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
    
    <!-- Documento existente -->
    <div id="existing-document-<?php echo e($name); ?>" <?php if(!$documentUrl): ?> style="display: none;" <?php endif; ?> class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 mb-2">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate" id="document-name-<?php echo e($name); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($documentName): ?> <?php echo e($documentName); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Documento anexado</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?php echo e($documentUrl ?? '#'); ?>" target="_blank" id="document-link-<?php echo e($name); ?>" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300" title="Visualizar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </a>
            <button 
                type="button"
                onclick="document.getElementById('remove_<?php echo e($name); ?>').value = '1'; document.getElementById('existing-document-<?php echo e($name); ?>').style.display = 'none';"
                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                title="Remover"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <input type="hidden" id="remove_<?php echo e($name); ?>" name="remove_<?php echo e($name); ?>" value="0">
    </div>
    
    <!-- Preview do novo arquivo selecionado -->
    <div id="new-document-<?php echo e($name); ?>" x-show="fileName" x-cloak style="display: none;" class="flex items-center gap-3 p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg border border-indigo-200 dark:border-indigo-800 mb-2">
        <div class="flex-shrink-0">
            <template x-if="preview">
                <img :src="preview" alt="Preview" class="w-16 h-16 object-cover rounded">
            </template>
            <template x-if="!preview">
                <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </template>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-indigo-900 dark:text-indigo-100 truncate" x-text="fileName"></p>
            <p class="text-xs text-indigo-600 dark:text-indigo-400" x-text="fileSize ? fileSize + ' MB' : ''"></p>
        </div>
        <button 
            type="button"
            @click="removeNewFile()"
            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
            title="Remover"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <!-- Área de upload -->
    <div class="mt-2">
        <label 
            for="<?php echo e($name); ?>"
            @dragover.prevent="handleDragOver"
            @dragleave.prevent="handleDragLeave"
            @drop.prevent="handleDrop"
            :class="{ 'border-indigo-500 dark:border-indigo-400 bg-indigo-50 dark:bg-indigo-900/30': isDragging, 'border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50': !isDragging }"
            class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
        >
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-semibold">Clique para selecionar</span> ou arraste e solte
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">PDF, DOC, DOCX, XLS, XLSX, Imagens, ZIP, RAR (Máx: 10MB)</p>
            </div>
            <input 
                type="file" 
                id="<?php echo e($name); ?>" 
                name="<?php echo e($name); ?>"
                accept="<?php echo e($accept); ?>"
                class="hidden"
                @change="handleFileSelect($event)"
                <?php if($required && !$documentUrl): ?> required <?php endif; ?>
            />
        </label>
    </div>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="text-red-500 dark:text-red-400 text-xs mt-1"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/components/document-upload.blade.php ENDPATH**/ ?>