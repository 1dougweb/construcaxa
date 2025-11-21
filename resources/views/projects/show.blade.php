<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes da Obra') }}
        </h2>
    </x-slot>

<div class="p-4">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $project->name }} <span class="text-gray-400">({{ $project->code }})</span></h1>
            <p class="text-sm text-gray-600 mt-1">Status: {{ $project->status }} · Progresso: {{ $project->progress_percentage }}%</p>
        </div>
        <div class="flex space-x-2">
            @can('manage finances')
            <a href="{{ route('projects.financial-balance', $project) }}" class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                <i class="bi bi-cash-coin mr-1"></i> Balanço Financeiro
            </a>
            @endcan
            @can('edit projects')
            <a href="{{ route('projects.edit', $project) }}" class="px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Editar</a>
            @endcan
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-md shadow p-4">
                <h2 class="font-medium text-gray-900 mb-3">Resumo</h2>
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                    <div><span class="text-gray-500">Endereço:</span> {{ $project->address ?: '-' }}</div>
                    <div><span class="text-gray-500">Início:</span> {{ optional($project->start_date)->format('d/m/Y') ?: '-' }}</div>
                    <div><span class="text-gray-500">Previsão:</span> {{ optional($project->end_date_estimated)->format('d/m/Y') ?: '-' }}</div>
                    <div><span class="text-gray-500">Equipe:</span> {{ $project->employees->count() }} membros</div>
                </div>
            </div>

            <div class="bg-white rounded-md shadow p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-medium text-gray-900">Timeline</h2>
                    @can('post project-updates')
                    <form action="{{ route('projects.updates.store', $project) }}" method="POST" class="flex items-end gap-2">
                        @csrf
                        <div>
                            <label class="block text-xs text-gray-600">Tipo</label>
                            <select name="type" class="border-gray-300 rounded-md text-sm">
                                <option value="note">Nota</option>
                                <option value="issue">Problema</option>
                                <option value="material_missing">Material faltante</option>
                                <option value="progress">Progresso</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600">Mensagem</label>
                            <input name="message" class="border-gray-300 rounded-md text-sm w-64" placeholder="Descreva a atualização" required>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600">Δ Progresso</label>
                            <input name="progress_delta" type="number" class="border-gray-300 rounded-md text-sm w-24" placeholder="ex: 5">
                        </div>
                        <button class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm">Postar</button>
                    </form>
                    @endcan
                </div>
                <ul class="space-y-4">
                    @forelse($project->updates()->latest()->take(20)->get() as $update)
                    <li class="border-l-2 pl-3 {{ $update->type === 'issue' ? 'border-red-400' : 'border-gray-300' }}">
                        <div class="text-sm text-gray-700">
                            <span class="font-medium">{{ $update->user->name }}</span>
                            <span class="text-gray-500">· {{ $update->created_at->format('d/m/Y H:i') }}</span>
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100">{{ $update->type }}</span>
                        </div>
                        <div class="text-sm text-gray-800">{{ $update->message }}</div>
                    </li>
                    @empty
                    <li class="text-sm text-gray-500">Nenhuma atualização ainda.</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white rounded-md shadow p-4">
                <h2 class="font-medium text-gray-900 mb-4">Tarefas</h2>

                <form action="{{ route('projects.tasks.store', $project) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="flex gap-2">
                        <input name="title" class="border-gray-300 rounded-md text-sm w-full" placeholder="Adicionar nova tarefa e pressionar Adicionar" required>
                        <input type="date" name="due_date" class="border-gray-300 rounded-md text-sm">
                        <button class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm">Adicionar</button>
                    </div>
                </form>

                @php
                    $tasks = $project->tasks()
                        ->orderByRaw("CASE status WHEN 'todo' THEN 0 WHEN 'in_progress' THEN 1 ELSE 2 END")
                        ->orderBy('sort_order')
                        ->orderByDesc('id')
                        ->get();
                @endphp

                <ul class="divide-y">
                    @forelse($tasks as $task)
                        <li class="py-3 text-sm flex items-center justify-between" x-data="{ open:false }">
                            <div class="flex items-start gap-3">
                                <form action="{{ route('projects.tasks.status', [$project, $task]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $task->status === 'done' ? 'todo' : 'done' }}">
                                    <input type="checkbox" {{ $task->status === 'done' ? 'checked' : '' }} onchange="this.form.submit()" class="h-4 w-4 text-indigo-600 rounded border-gray-300">
                                </form>
                                <div>
                                    <div class="font-medium {{ $task->status === 'done' ? 'line-through text-gray-400' : 'text-gray-900' }}">{{ $task->title }}</div>
                                    <div class="text-xs text-gray-500 flex items-center gap-2">
                                        <span>{{ $task->status === 'in_progress' ? 'Em progresso' : ($task->status === 'done' ? 'Concluída' : 'A fazer') }}</span>
                                        @if($task->due_date)
                                            @php
                                                $days = now()->startOfDay()->diffInDays($task->due_date, false);
                                                $badgeClass = $days < 0
                                                    ? 'bg-red-100 text-red-700'
                                                    : ($days <= 2
                                                        ? 'bg-yellow-100 text-yellow-700'
                                                        : 'bg-green-100 text-green-700');
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded {{ $badgeClass }}">Vence: {{ $task->due_date->format('d/m/Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" class="text-indigo-600 text-xs" @click="open=true">Detalhes</button>
                                <form action="{{ route('projects.tasks.delete', [$project, $task]) }}" method="POST" onsubmit="return confirm('Remover esta tarefa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 text-xs">Remover</button>
                                </form>
                            </div>

                            <!-- Modal de edição -->
                            <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
                                <div class="bg-white rounded-md shadow p-4 w-full max-w-lg">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="font-medium text-gray-900">Editar Tarefa</h3>
                                        <button class="text-gray-500" @click="open=false">✕</button>
                                    </div>
                                    <form action="{{ route('projects.tasks.update', [$project, $task]) }}" method="POST" class="space-y-3">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">Título</label>
                                            <input name="title" class="border-gray-300 rounded-md text-sm w-full" value="{{ $task->title }}" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">Descrição</label>
                                            <textarea name="description" class="border-gray-300 rounded-md text-sm w-full" rows="4">{{ $task->description }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">Vencimento</label>
                                            <input type="date" name="due_date" class="border-gray-300 rounded-md text-sm" value="{{ optional($task->due_date)->format('Y-m-d') }}">
                                        </div>
                                        <div class="flex items-center justify-end gap-2 pt-2">
                                            <button type="button" class="px-3 py-2 text-sm text-gray-600" @click="open=false">Cancelar</button>
                                            <button class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm">Salvar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="py-3 text-sm text-gray-500">Nenhuma tarefa adicionada ainda.</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white rounded-md shadow p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-medium text-gray-900">Fotos do Projeto</h2>
                    @can('post project-updates')
                    <button onclick="openPhotoUploadModal('{{ route('projects.photos.upload', $project) }}')" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                        Adicionar Foto
                    </button>
                    @endcan
                </div>
                
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
                        @can('post project-updates')
                        <p class="mt-2">Clique em "Adicionar Foto" para começar.</p>
                        @endcan
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-md shadow p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-medium text-gray-900">Arquivos</h2>
                    @can('post project-updates')
                    <button onclick="openFileUploadModal('{{ route('projects.files.upload', $project) }}')" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                        Adicionar Arquivo
                    </button>
                    @endcan
                </div>

                <!-- Lista de Arquivos -->
                <div class="space-y-2">
                    @forelse($project->files as $file)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-md hover:bg-gray-50">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <span class="text-2xl flex-shrink-0">{{ $file->file_icon }}</span>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 truncate" title="{{ $file->original_name }}">
                                        {{ $file->original_name }}
                                    </div>
                                    <div class="text-xs text-gray-500 flex items-center gap-3 mt-1">
                                        <span>{{ $file->formatted_size }}</span>
                                        <span>•</span>
                                        <span>{{ $file->created_at->format('d/m/Y H:i') }}</span>
                                        <span>•</span>
                                        <span>{{ $file->user->name }}</span>
                                        @if($file->description)
                                            <span>•</span>
                                            <span class="italic">{{ Str::limit($file->description, 30) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                                <a href="{{ route('projects.files.download', [$project, $file]) }}" 
                                   class="px-3 py-1 text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded">
                                    Download
                                </a>
                                @if($file->user_id === auth()->id() || auth()->user()->can('edit projects') || auth()->user()->hasAnyRole(['manager', 'admin']))
                                    <form action="{{ route('projects.files.delete', [$project, $file]) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Tem certeza que deseja remover este arquivo?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-3 py-1 text-xs font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded">
                                            Remover
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-sm text-gray-500">
                            <p>Nenhum arquivo enviado ainda.</p>
                            @can('post project-updates')
                            <p class="mt-2">Clique em "Adicionar Arquivo" para começar.</p>
                            @endcan
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Budget Information -->
            @if($project->budgets->count() > 0)
            <div class="bg-white rounded-md shadow p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-medium text-gray-900">Orçamentos</h2>
                    <a href="{{ route('budgets.index', ['project_id' => $project->id]) }}" class="text-xs text-indigo-600 hover:text-indigo-800">
                        Ver todos
                    </a>
                </div>
                <div class="space-y-3">
                    @foreach($project->budgets->take(3) as $budget)
                        <div class="border rounded-md p-3 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <div class="font-medium text-sm text-gray-900">
                                        Orçamento #{{ $budget->id }} - v{{ $budget->version }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Criado em {{ $budget->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full {{ $budget->status_color }}">
                                    {{ $budget->status_label }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-700 mb-2">
                                <span class="font-medium">Total:</span> R$ {{ number_format($budget->total, 2, ',', '.') }}
                            </div>
                            @if($budget->approved_at)
                                <div class="text-xs text-gray-600 mb-1">
                                    <span class="font-medium">Aprovado em:</span> {{ $budget->approved_at->format('d/m/Y H:i') }}
                                </div>
                                @if($budget->approver)
                                    <div class="text-xs text-gray-600">
                                        <span class="font-medium">Por:</span> {{ $budget->approver->name }}
                                    </div>
                                @endif
                            @endif
                            @if($project->os_number && $budget->status === 'approved')
                                <div class="mt-2 pt-2 border-t">
                                    <div class="text-xs font-medium text-indigo-600">
                                        OS: {{ $project->os_number }}
                                    </div>
                                </div>
                            @endif
                            <div class="mt-2 flex space-x-2">
                                <a href="{{ route('budgets.edit', $budget) }}" class="text-xs text-indigo-600 hover:text-indigo-800">
                                    Editar
                                </a>
                                <a href="{{ route('budgets.pdf', $budget) }}" class="text-xs text-gray-600 hover:text-gray-800">
                                    PDF
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="bg-white rounded-md shadow p-4">
                <h2 class="font-medium text-gray-900 mb-3">Equipe</h2>
                <ul class="divide-y">
                    @forelse($project->employees as $member)
                        <li class="py-2 text-sm">
                            <div class="font-medium">{{ $member->user->name }}</div>
                            <div class="text-gray-500">{{ $member->position ?: '-' }}</div>
                        </li>
                    @empty
                        <li class="py-2 text-sm text-gray-500">Nenhum membro da equipe atribuído.</li>
                    @endforelse
                </ul>
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
function openFileUploadModal(uploadUrl) {
    const modalContent = document.getElementById('file-upload-modal-content');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    modalContent.innerHTML = `
        <form action="${uploadUrl}" method="POST" enctype="multipart/form-data" id="fileUploadForm">
            <input type="hidden" name="_token" value="${csrfToken}">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Selecionar Arquivos</label>
                    <input type="file" 
                           name="files[]" 
                           multiple 
                           accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.zip,.rar"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-md p-2"
                           required>
                    <p class="mt-1 text-xs text-gray-500">Tipos permitidos: Imagens, PDF, Documentos, Planilhas, Arquivos de texto, ZIP, RAR (Máx: 10MB por arquivo)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descrição (opcional)</label>
                    <textarea name="description" 
                              rows="3" 
                              class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                              placeholder="Descrição dos arquivos..."></textarea>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4">
                    <button type="button" 
                            onclick="closeFileUploadModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                        Enviar Arquivos
                    </button>
                </div>
            </div>
        </form>
    `;
    window.dispatchEvent(new CustomEvent('open-file-upload-modal'));
}

function closeFileUploadModal() {
    window.dispatchEvent(new CustomEvent('close-file-upload-modal'));
}

function openPhotoUploadModal(uploadUrl) {
    const modalContent = document.getElementById('file-upload-modal-content');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    modalContent.innerHTML = `
        <form action="${uploadUrl}" method="POST" enctype="multipart/form-data" id="photoUploadForm">
            <input type="hidden" name="_token" value="${csrfToken}">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Selecionar Foto</label>
                    <input type="file" 
                           name="photo" 
                           accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-md p-2"
                           required>
                    <p class="mt-1 text-xs text-gray-500">Formatos: JPG, PNG, GIF, WebP (Máx: 5MB)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Legenda (opcional)</label>
                    <textarea name="caption" 
                              rows="3" 
                              class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                              placeholder="Descreva a foto..."></textarea>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4">
                    <button type="button" 
                            onclick="closeFileUploadModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                        Enviar Foto
                    </button>
                </div>
            </div>
        </form>
    `;
    window.dispatchEvent(new CustomEvent('open-file-upload-modal'));
}

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


