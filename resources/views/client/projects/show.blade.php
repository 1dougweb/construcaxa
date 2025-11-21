<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $project->name }}
        </h2>
    </x-slot>

<div class="p-4">
    <p class="text-sm text-gray-600">Código: {{ $project->code }} · Progresso: {{ $project->progress_percentage }}%</p>

    <div class="mt-6 grid grid-cols-1 gap-6">
        <div class="bg-white rounded-md shadow p-4">
            <h2 class="font-medium text-gray-900 mb-3">Resumo</h2>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                <div><span class="text-gray-500">Status:</span> {{ $project->status }}</div>
                <div><span class="text-gray-500">Endereço:</span> {{ $project->address ?: '-' }}</div>
                <div><span class="text-gray-500">Início:</span> {{ optional($project->start_date)->format('d/m/Y') ?: '-' }}</div>
                <div><span class="text-gray-500">Previsão:</span> {{ optional($project->end_date_estimated)->format('d/m/Y') ?: '-' }}</div>
            </div>
        </div>

        <div class="bg-white rounded-md shadow p-4">
            <h2 class="font-medium text-gray-900 mb-3">Atualizações</h2>
            <ul class="space-y-4">
                @forelse($project->updates()->latest()->take(20)->get() as $update)
                <li class="border-l-2 pl-3 border-gray-300">
                    <div class="text-sm text-gray-700">
                        <span class="text-gray-500">{{ $update->created_at->format('d/m/Y H:i') }}</span>
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100">{{ $update->type }}</span>
                    </div>
                    <div class="text-sm text-gray-800">{{ $update->message }}</div>
                </li>
                @empty
                <li class="text-sm text-gray-500">Sem atualizações.</li>
                @endforelse
            </ul>
        </div>

        <div class="bg-white rounded-md shadow p-4">
            <h2 class="font-medium text-gray-900 mb-4">Fotos do Projeto</h2>
            
            @php
                $photos = $project->photos()->with('user')->latest()->get();
            @endphp
            
            @if($photos->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="photo-gallery">
                    @foreach($photos as $photo)
                        <div class="group relative cursor-pointer photo-item" 
                             data-photo-url="{{ asset('storage/' . $photo->path) }}"
                             data-photo-caption="{{ $photo->caption ?? 'Sem legenda' }}"
                             data-photo-date="{{ $photo->created_at->format('d/m/Y H:i') }}"
                             data-photo-user="{{ $photo->user->name ?? 'Desconhecido' }}"
                             data-photo-index="{{ $loop->index }}">
                            <div class="relative overflow-hidden rounded-lg aspect-square bg-gray-100">
                                <img src="{{ asset('storage/' . $photo->path) }}" 
                                     alt="{{ $photo->caption ?? 'Foto do projeto' }}" 
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                     loading="lazy">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/60 to-transparent text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="font-medium truncate">{{ $photo->caption ?? 'Sem legenda' }}</div>
                                    <div class="text-xs text-gray-200 mt-1">{{ $photo->created_at->format('d/m/Y H:i') }}</div>
                                    <div class="text-xs text-gray-300">{{ $photo->user->name ?? 'Desconhecido' }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-sm text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p>Nenhuma foto enviada ainda.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
#lightbox {
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}
#lightbox.show {
    opacity: 1;
}
#lightbox.hidden {
    display: none !important;
}
#lightbox-backdrop {
    background-color: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
#lightbox-image {
    transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    max-width: 90vw;
    max-height: 85vh;
}
.prev-btn:disabled,
.next-btn:disabled {
    pointer-events: none;
    opacity: 0.3;
}
#lightbox-close-btn {
    z-index: 60;
}
#lightbox-close-btn:hover {
    background-color: rgba(255, 255, 255, 0.2);
}
</style>
@endpush

@push('scripts')
<script>
// Lightbox para galeria de fotos
(function() {
    'use strict';
    
    let currentIndex = 0;
    let photos = [];
    let lightbox = null;
    let lightboxContent = null;
    let lightboxImage = null;
    let lightboxInfo = null;
    let isOpen = false;
    
    // Inicializar lightbox
    function initLightbox() {
        // Criar estrutura do lightbox
        lightbox = document.createElement('div');
        lightbox.id = 'lightbox';
        lightbox.className = 'fixed inset-0 z-50 hidden';
        lightbox.innerHTML = `
            <div id="lightbox-backdrop" class="absolute inset-0" onclick="PhotoLightbox.close()"></div>
            <button id="lightbox-close-btn" class="absolute top-4 right-4 text-white hover:text-gray-300 p-2 rounded-full transition-all duration-200" onclick="PhotoLightbox.close()" aria-label="Fechar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <button class="absolute left-4 top-1/2 -translate-y-1/2 z-50 text-white hover:text-gray-300 p-2 rounded-full hover:bg-white/10 transition-all duration-200 prev-btn" onclick="PhotoLightbox.prev()" aria-label="Anterior">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button class="absolute right-4 top-1/2 -translate-y-1/2 z-50 text-white hover:text-gray-300 p-2 rounded-full hover:bg-white/10 transition-all duration-200 next-btn" onclick="PhotoLightbox.next()" aria-label="Próxima">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
                <div class="max-w-4xl w-full flex flex-col items-center pointer-events-auto">
                    <div class="relative w-full flex items-center justify-center mb-3">
                        <img id="lightbox-image" class="object-contain rounded-lg shadow-2xl" alt="" loading="eager" style="max-width: 85vw; max-height: 75vh;">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-white loading-spinner hidden"></div>
                        </div>
                    </div>
                    <div id="lightbox-info" class="text-white text-center px-4">
                        <div class="text-lg font-semibold mb-1 caption"></div>
                        <div class="text-sm text-gray-300 info"></div>
                        <div class="text-xs text-gray-400 mt-1 counter"></div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(lightbox);
        
        lightboxImage = document.getElementById('lightbox-image');
        lightboxInfo = document.getElementById('lightbox-info');
        
        // Event listeners para teclado
        document.addEventListener('keydown', handleKeydown);
    }
    
    // Coletar fotos da galeria
    function collectPhotos() {
        const gallery = document.getElementById('photo-gallery');
        if (!gallery) return [];
        
        const items = gallery.querySelectorAll('.photo-item');
        photos = Array.from(items).map(item => ({
            url: item.getAttribute('data-photo-url'),
            caption: item.getAttribute('data-photo-caption') || 'Sem legenda',
            date: item.getAttribute('data-photo-date'),
            user: item.getAttribute('data-photo-user'),
            index: parseInt(item.getAttribute('data-photo-index'))
        }));
        
        return photos;
    }
    
    // Atualizar conteúdo do lightbox
    function updateLightbox() {
        if (!lightbox || photos.length === 0) return;
        
        const photo = photos[currentIndex];
        if (!photo) return;
        
        // Mostrar loading
        const spinner = lightbox.querySelector('.loading-spinner');
        if (spinner) spinner.classList.remove('hidden');
        if (lightboxImage) {
            lightboxImage.style.opacity = '0';
            lightboxImage.style.transform = 'scale(0.95)';
        }
        
        // Carregar imagem
        const img = new Image();
        img.onload = function() {
            if (lightboxImage) {
                lightboxImage.src = photo.url;
                lightboxImage.alt = photo.caption;
                setTimeout(() => {
                    lightboxImage.style.opacity = '1';
                    lightboxImage.style.transform = 'scale(1)';
                }, 50);
            }
            if (spinner) spinner.classList.add('hidden');
        };
        img.onerror = function() {
            if (spinner) spinner.classList.add('hidden');
            if (lightboxImage) {
                lightboxImage.src = photo.url;
                lightboxImage.style.opacity = '1';
                lightboxImage.style.transform = 'scale(1)';
            }
        };
        img.src = photo.url;
        
        // Atualizar informações
        if (lightboxInfo) {
            const captionEl = lightboxInfo.querySelector('.caption');
            const infoEl = lightboxInfo.querySelector('.info');
            const counterEl = lightboxInfo.querySelector('.counter');
            
            if (captionEl) captionEl.textContent = photo.caption;
            if (infoEl) infoEl.textContent = `${photo.date} · ${photo.user}`;
            if (counterEl) counterEl.textContent = `${currentIndex + 1} de ${photos.length}`;
        }
        
        // Atualizar botões de navegação
        const prevBtn = lightbox.querySelector('.prev-btn');
        const nextBtn = lightbox.querySelector('.next-btn');
        
        if (prevBtn) {
            if (currentIndex === 0) {
                prevBtn.disabled = true;
            } else {
                prevBtn.disabled = false;
            }
        }
        
        if (nextBtn) {
            if (currentIndex === photos.length - 1) {
                nextBtn.disabled = true;
            } else {
                nextBtn.disabled = false;
            }
        }
    }
    
    // Abrir lightbox
    function open(index) {
        if (isOpen) return;
        
        collectPhotos();
        if (photos.length === 0) return;
        
        if (!lightbox) {
            initLightbox();
        }
        
        currentIndex = Math.max(0, Math.min(index, photos.length - 1));
        
        lightbox.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        isOpen = true;
        
        // Animação de entrada suave
        requestAnimationFrame(() => {
            setTimeout(() => {
                lightbox.classList.add('show');
                updateLightbox();
            }, 10);
        });
    }
    
    // Fechar lightbox
    function close() {
        if (!isOpen || !lightbox) return;
        
        lightbox.classList.remove('show');
        setTimeout(() => {
            lightbox.classList.add('hidden');
            document.body.style.overflow = '';
            isOpen = false;
        }, 300);
    }
    
    // Foto anterior
    function prev() {
        if (currentIndex > 0) {
            currentIndex--;
            updateLightbox();
        }
    }
    
    // Próxima foto
    function next() {
        if (currentIndex < photos.length - 1) {
            currentIndex++;
            updateLightbox();
        }
    }
    
    // Handler de teclado
    function handleKeydown(e) {
        if (!isOpen) return;
        
        switch(e.key) {
            case 'Escape':
                close();
                break;
            case 'ArrowLeft':
                prev();
                break;
            case 'ArrowRight':
                next();
                break;
        }
    }
    
    // Inicializar eventos dos itens da galeria
    function initGallery() {
        const gallery = document.getElementById('photo-gallery');
        if (!gallery) return;
        
        const items = gallery.querySelectorAll('.photo-item');
        items.forEach(item => {
            item.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-photo-index'));
                open(index);
            });
        });
    }
    
    // API pública
    window.PhotoLightbox = {
        open: open,
        close: close,
        prev: prev,
        next: next
    };
    
    // Inicializar quando o DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGallery);
    } else {
        initGallery();
    }
})();
</script>
@endpush

</x-app-layout>
