@props([
    'name' => 'profile_photo',
    'label' => 'Foto de Perfil',
    'required' => false,
    'existingPhotoPath' => null,
])

@php
use Illuminate\Support\Facades\Storage;
    $photoPath = $existingPhotoPath;
    $photoUrl = null;
    if ($photoPath && $photoPath !== '' && $photoPath !== null) {
        // Usar exatamente a mesma lógica do show que funciona
        $photoUrl = Storage::url($photoPath);
    }
@endphp

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
                        const existingDiv = document.getElementById('existing-photo-{{ $name }}');
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
            const fileInput = document.getElementById('{{ $name }}');
            if (fileInput && e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                this.handleFileSelect({ target: fileInput });
            }
        },
        removePhoto() {
            this.preview = null;
            const fileInput = document.getElementById('{{ $name }}');
            if (fileInput) {
                fileInput.value = '';
                const newInput = fileInput.cloneNode(true);
                fileInput.parentNode.replaceChild(newInput, fileInput);
            }
            // Adicionar campo hidden para indicar remoção
            let removeInput = document.getElementById('remove_{{ $name }}');
            if (!removeInput) {
                removeInput = document.createElement('input');
                removeInput.type = 'hidden';
                removeInput.id = 'remove_{{ $name }}';
                removeInput.name = 'remove_{{ $name }}';
                removeInput.value = '1';
                fileInput.parentNode.appendChild(removeInput);
            } else {
                removeInput.value = '1';
            }
            const existingDiv = document.getElementById('existing-photo-{{ $name }}');
            if (existingDiv) existingDiv.style.display = 'none';
        }
    }"
>
    <x-label for="{{ $name }}" value="{{ $label }}" />
    <div class="mt-1 relative" style="width: 200px; height: 200px;">
        <label 
            for="{{ $name }}"
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
            @if($photoUrl)
                <div id="existing-photo-{{ $name }}" class="relative w-full h-full group" style="display: block;">
                    <img src="{{ $photoUrl }}" alt="Foto" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center">
                        <span class="text-white opacity-0 group-hover:opacity-100 text-sm font-medium">Clique para trocar</span>
                    </div>
                </div>
            @else
                <!-- Placeholder - só aparece se não há foto existente -->
                <div x-show="!preview" class="absolute inset-0 flex flex-col items-center justify-center text-center text-gray-400 dark:text-gray-500 p-4">
                    <svg class="h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Arraste e solte</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">ou clique para selecionar</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">JPEG, PNG, JPG, GIF, WEBP, AVIF até 2MB</p>
                </div>
            @endif
        </label>
        
        <input 
            type="file" 
            id="{{ $name }}" 
            name="{{ $name }}"
            accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/avif"
            class="hidden"
            @if($required) required @endif
            @change="handleFileSelect($event)"
        />
        
        @if($photoUrl)
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
        @endif
        
        @error($name)
            <p class="text-red-500 text-xs mt-1 absolute -bottom-6 left-0">{{ $message }}</p>
        @enderror
    </div>
</div>
