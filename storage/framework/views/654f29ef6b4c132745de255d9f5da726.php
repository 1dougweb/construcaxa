<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => 'profile_photo',
    'label' => 'Foto de Perfil',
    'required' => false,
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
    'name' => 'profile_photo',
    'label' => 'Foto de Perfil',
    'required' => false,
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
use Illuminate\Support\Facades\Storage;
    $photoPath = $existingPhotoPath;
    $photoUrl = null;
    if ($photoPath && $photoPath !== '' && $photoPath !== null) {
        // Usar exatamente a mesma lógica do show que funciona
        $photoUrl = Storage::url($photoPath);
    }
?>

<div 
    x-data="{
        isDragging: false,
        preview: null,
        handleFileSelect(e) {
            const file = e.target.files[0] || (e.dataTransfer && e.dataTransfer.files[0]);
            if (file) {
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'image/avif'];
                if (validTypes.includes(file.type) || file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.preview = e.target.result;
                        const existingDiv = document.getElementById('existing-photo-<?php echo e($name); ?>');
                        if (existingDiv) existingDiv.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            }
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
        },
        removePhoto() {
            this.preview = null;
            const fileInput = document.getElementById('<?php echo e($name); ?>');
            if (fileInput) {
                fileInput.value = '';
                const newInput = fileInput.cloneNode(true);
                fileInput.parentNode.replaceChild(newInput, fileInput);
            }
            // Adicionar campo hidden para indicar remoção
            let removeInput = document.getElementById('remove_<?php echo e($name); ?>');
            if (!removeInput) {
                removeInput = document.createElement('input');
                removeInput.type = 'hidden';
                removeInput.id = 'remove_<?php echo e($name); ?>';
                removeInput.name = 'remove_<?php echo e($name); ?>';
                removeInput.value = '1';
                fileInput.parentNode.appendChild(removeInput);
            } else {
                removeInput.value = '1';
            }
            const existingDiv = document.getElementById('existing-photo-<?php echo e($name); ?>');
            if (existingDiv) existingDiv.style.display = 'none';
        }
    }"
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
    <div class="mt-1 relative" style="width: 200px; height: 200px;">
        <label 
            for="<?php echo e($name); ?>"
            @dragover.prevent="handleDragOver"
            @dragleave.prevent="handleDragLeave"
            @drop.prevent="handleDrop"
            :class="{ 'border-indigo-500 dark:border-indigo-400 bg-indigo-50 dark:bg-indigo-900/30': isDragging, 'border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700': !isDragging }"
            class="w-full h-full border-2 border-dashed rounded-lg overflow-hidden flex items-center justify-center cursor-pointer transition-all duration-200 hover:border-indigo-400 dark:hover:border-indigo-500 hover:bg-indigo-50/50 dark:hover:bg-indigo-900/30 relative"
        >
            <!-- Preview de nova foto -->
            <div x-show="preview" x-cloak class="relative w-full h-full group" style="display: none;">
                <img :src="preview" alt="Preview" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center">
                    <span class="text-white opacity-0 group-hover:opacity-100 text-sm font-medium">Clique para trocar</span>
                </div>
            </div>
            
            <!-- Foto existente - EXATAMENTE COMO NO photo-upload QUE FUNCIONA -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($photoUrl): ?>
                <div id="existing-photo-<?php echo e($name); ?>" class="relative w-full h-full group" style="display: block;">
                    <img src="<?php echo e($photoUrl); ?>" alt="Foto" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center">
                        <span class="text-white opacity-0 group-hover:opacity-100 text-sm font-medium">Clique para trocar</span>
                    </div>
                </div>
            <?php else: ?>
                <!-- Placeholder - só aparece se não há foto existente -->
                <div x-show="!preview" class="absolute inset-0 flex flex-col items-center justify-center text-center text-gray-400 dark:text-gray-500 p-4">
                    <svg class="h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Arraste e solte</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">ou clique para selecionar</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">JPEG, PNG, JPG, GIF, WEBP, AVIF até 2MB</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </label>
        
        <input 
            type="file" 
            id="<?php echo e($name); ?>" 
            name="<?php echo e($name); ?>"
            accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/avif"
            class="hidden"
            <?php if($required): ?> required <?php endif; ?>
            @change="handleFileSelect($event)"
        />
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($photoUrl): ?>
            <button 
                type="button"
                @click="removePhoto()"
                class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-full p-2 shadow-lg transition-all duration-200 hover:scale-110 z-20"
                title="Excluir foto"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-red-500 text-xs mt-1 absolute -bottom-6 left-0"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/components/photo-upload-simple.blade.php ENDPATH**/ ?>