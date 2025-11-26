<div x-data="mediaCapture()" class="media-capture-component">
    <!-- Input File do Livewire (oculto) -->
    <input 
        type="file" 
        accept="image/*" 
        multiple
        wire:model="tempPhotos"
        x-ref="livewireFileInput"
        class="hidden"
        wire:loading.attr="disabled"
    >
    
    <!-- Input para Câmera (mobile) -->
    <input 
        type="file" 
        accept="image/*" 
        capture="environment"
        x-ref="cameraInput"
        @change="handleFiles($event)"
        class="hidden"
    >
    
    <!-- Input para Galeria -->
    <input 
        type="file" 
        accept="image/*" 
        multiple
        x-ref="galleryInput"
        @change="handleFiles($event)"
        class="hidden"
    >
    
    <!-- Botão Principal -->
    <button 
        @click="openMediaSelector(@js($context))"
        class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
        type="button"
    >
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        Adicionar Fotos
    </button>
    
    <!-- Modal de Opções (Mobile) -->
    <div 
        x-show="showMediaOptions" 
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        @click.away="showMediaOptions = false"
        style="display: none;"
    >
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Adicionar Fotos</h3>
            <div class="space-y-3">
                <button 
                    @click="openCamera()"
                    class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                >
                    Tirar Foto
                </button>
                <button 
                    @click="openGallery()"
                    class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                >
                    Escolher da Galeria
                </button>
                <button 
                    @click="showMediaOptions = false"
                    class="w-full px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500"
                >
                    Cancelar
                </button>
            </div>
        </div>
    </div>
    
    <!-- Grid de Preview -->
    <div class="mt-4 grid grid-cols-3 gap-2" x-show="previews.length > 0" style="display: none;">
        <template x-for="(preview, index) in previews" :key="index">
            <div class="relative">
                <img :src="preview.url" class="w-full h-24 object-cover rounded">
                <button 
                    @click="removePreview(index)" 
                    class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs"
                >
                    ×
                </button>
            </div>
        </template>
    </div>
    
    <!-- Fotos já enviadas -->
    @if(isset($photos) && is_array($photos) && count($photos) > 0)
        <div class="mt-4 grid grid-cols-3 gap-2">
            @foreach($photos as $photoIndex => $photoPath)
                <div class="relative">
                    <img src="{{ asset('storage/' . $photoPath) }}" class="w-full h-24 object-cover rounded">
                    <button 
                        wire:click="removePhoto({{ $context['index'] ?? $context['envIndex'] }}, {{ $photoIndex }}{{ isset($context['elemIndex']) ? ', ' . $context['elemIndex'] : '' }})"
                        class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs"
                    >
                        ×
                    </button>
                </div>
            @endforeach
        </div>
    @endif
    
    <!-- Loading durante upload -->
    <div wire:loading wire:target="tempPhotos" class="mt-2 text-sm text-gray-600 dark:text-gray-400">
        Enviando fotos...
    </div>
</div>

<script>
function mediaCapture() {
    return {
        previews: [],
        isMobile: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
        showMediaOptions: false,
        context: null,
        
        openMediaSelector(context) {
            this.context = context;
            // Salvar contexto no Livewire
            @this.set('uploadContext', context);
            
            if (this.isMobile) {
                this.showMediaOptions = true;
            } else {
                this.$refs.livewireFileInput.click();
            }
        },
        
        openCamera() {
            this.$refs.cameraInput.click();
        },
        
        openGallery() {
            this.$refs.galleryInput.click();
        },
        
        handleFiles(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => {
                if (this.validateFile(file)) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.previews.push({
                            file: file,
                            url: e.target.result,
                            name: file.name,
                            size: file.size
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            // Se for do input Livewire, já fazer upload
            if (event.target === this.$refs.livewireFileInput) {
                // O Livewire já processa automaticamente
            } else {
                // Para câmera/galeria, transferir para input Livewire
                const dataTransfer = new DataTransfer();
                files.forEach(file => dataTransfer.items.add(file));
                this.$refs.livewireFileInput.files = dataTransfer.files;
                this.$refs.livewireFileInput.dispatchEvent(new Event('change', { bubbles: true }));
            }
            
            event.target.value = '';
        },
        
        validateFile(file) {
            const maxSize = 5 * 1024 * 1024;
            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
            
            if (!allowedTypes.includes(file.type)) {
                alert('Tipo de arquivo não permitido. Use JPG, PNG ou WEBP.');
                return false;
            }
            
            if (file.size > maxSize) {
                alert('Arquivo muito grande. Máximo 5MB.');
                return false;
            }
            
            return true;
        },
        
        removePreview(index) {
            this.previews.splice(index, 1);
        }
    }
}
</script>
