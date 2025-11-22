@props(['label' => 'Fotos', 'maxPhotos' => 20])

<div class="space-y-2">
    <x-label :value="$label" />
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Existing Photos -->
        @if(isset($photos) && is_array($photos) && count($photos) > 0)
            @foreach($photos as $index => $photo)
                <div class="relative group aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 border-gray-200">
                    <img src="{{ asset('storage/' . $photo) }}" alt="Foto" class="w-full h-full object-cover">
                    <button 
                        type="button"
                        wire:click="confirmDeletePhoto({{ $index }})"
                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity z-10"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endforeach
        @endif
        
        <!-- Temp Photos (Uploading) -->
        @if(isset($tempPhotos) && is_array($tempPhotos))
            @foreach($tempPhotos as $index => $tempPhoto)
                <div class="relative aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 border-gray-200">
                    @if($tempPhoto)
                        <img src="{{ $tempPhoto->temporaryUrl() }}" alt="Uploading..." class="w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
                        <svg class="animate-spin h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            @endforeach
        @endif
        
        <!-- Upload Area -->
        @if((isset($photos) ? count($photos) : 0) + (isset($tempPhotos) ? count($tempPhotos) : 0) < $maxPhotos)
            <div 
                x-data="{
                    isDragging: false,
                    handleDrop(e) {
                        e.preventDefault();
                        this.isDragging = false;
                        const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
                        if (files.length > 0) {
                            files.forEach((file, index) => {
                                if (({{ isset($photos) ? count($photos) : 0 }} + {{ isset($tempPhotos) ? count($tempPhotos) : 0 }} + index) < {{ $maxPhotos }}) {
                                    @this.upload('tempPhotos.' + ({{ isset($tempPhotos) ? count($tempPhotos) : 0 }} + index), file);
                                }
                            });
                        }
                    },
                    handleFileSelect(e) {
                        const files = Array.from(e.target.files).filter(file => file.type.startsWith('image/'));
                        if (files.length > 0) {
                            files.forEach((file, index) => {
                                if (({{ isset($photos) ? count($photos) : 0 }} + {{ isset($tempPhotos) ? count($tempPhotos) : 0 }} + index) < {{ $maxPhotos }}) {
                                    @this.upload('tempPhotos.' + ({{ isset($tempPhotos) ? count($tempPhotos) : 0 }} + index), file);
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
                class="aspect-square border-2 border-dashed rounded-lg flex flex-col items-center justify-center cursor-pointer transition-colors"
                :class="isDragging ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300 hover:border-gray-400'"
            >
                <input 
                    type="file" 
                    x-ref="fileInput"
                    @change="handleFileSelect"
                    multiple
                    accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/avif"
                    class="hidden"
                >
                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <p class="text-sm text-gray-500 text-center px-4">
                    <span class="font-medium">Clique ou arraste</span><br>
                    para adicionar fotos
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    JPG, PNG, WEBP, AVIF
                </p>
            </div>
        @endif
    </div>
    
    @if(isset($showDeleteModal) && $showDeleteModal)
        <x-modal-confirm
            :show="$showDeleteModal"
            title="Excluir Foto"
            message="Tem certeza que deseja excluir esta foto?"
            confirm-text="Excluir"
            cancel-text="Cancelar"
            type="danger"
            confirm-action="deletePhoto"
            cancel-action="cancelDeletePhoto"
        />
    @endif
</div>
