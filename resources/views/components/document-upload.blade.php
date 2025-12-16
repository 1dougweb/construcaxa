@props([
    'name' => 'document_file',
    'label' => 'Documento',
    'required' => false,
    'existingDocumentPath' => null,
    'accept' => '.pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar',
])

@php
    $documentUrl = null;
    $documentName = null;
    if ($existingDocumentPath && $existingDocumentPath !== '' && $existingDocumentPath !== null) {
        $documentUrl = '/' . ltrim($existingDocumentPath, '/');
        $documentName = basename($existingDocumentPath);
    }
@endphp

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
                document.getElementById('new-document-{{ $name }}').style.display = 'flex';
            }
        },
        removeNewFile() {
            this.preview = null;
            this.fileName = null;
            this.fileSize = null;
            const fileInput = document.getElementById('{{ $name }}');
            if (fileInput) {
                fileInput.value = '';
            }
            document.getElementById('new-document-{{ $name }}').style.display = 'none';
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
        }
    }"
    class="space-y-2"
>
    <x-label for="{{ $name }}" value="{{ $label }}" />
    
    <!-- Documento existente -->
    <div id="existing-document-{{ $name }}" @if(!$documentUrl) style="display: none;" @endif class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 mb-2">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate" id="document-name-{{ $name }}">
                @if($documentName) {{ $documentName }} @endif
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Documento anexado</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ $documentUrl ?? '#' }}" target="_blank" id="document-link-{{ $name }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300" title="Visualizar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </a>
            <button 
                type="button"
                onclick="document.getElementById('remove_{{ $name }}').value = '1'; document.getElementById('existing-document-{{ $name }}').style.display = 'none';"
                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                title="Remover"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <input type="hidden" id="remove_{{ $name }}" name="remove_{{ $name }}" value="0">
    </div>
    
    <!-- Preview do novo arquivo selecionado -->
    <div id="new-document-{{ $name }}" x-show="fileName" x-cloak style="display: none;" class="flex items-center gap-3 p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg border border-indigo-200 dark:border-indigo-800 mb-2">
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
            for="{{ $name }}"
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
                id="{{ $name }}" 
                name="{{ $name }}"
                accept="{{ $accept }}"
                class="hidden"
                @change="handleFileSelect($event)"
                @if($required && !$documentUrl) required @endif
            />
        </label>
    </div>
    
    @error($name)
        <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
