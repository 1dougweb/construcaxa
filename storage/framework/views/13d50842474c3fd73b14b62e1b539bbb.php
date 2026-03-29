<div x-data="{ 
        dropping: false,
        internalDrag: false,
        openDeleteModal: false,
        itemToDelete: null,
        isDirToDelete: false,
        
        startDrag(event, path) {
            this.internalDrag = true;
            event.dataTransfer.setData('text/plain', path);
            event.dataTransfer.effectAllowed = 'move';
        },
        
        handleDropOnMain(event) {
            this.dropping = false;
            // Se for um arrasto interno (movimentação de itens), e caiu na mesma pasta raiz, não faz nada
            if (this.internalDrag) {
                this.internalDrag = false;
                return;
            }
            // Se for arrasto externo de arquivos (upload)
            if (event.dataTransfer.files && event.dataTransfer.files.length > 0) {
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').uploadMultiple('uploads', event.dataTransfer.files, () => {}, () => { 
                    $dispatch('notification', { type: 'error', message: 'Erro no upload' });
                }, (e) => {});
            }
        },

        handleDropOnTarget(event, destinationPath) {
            this.dropping = false;
            let sourcePath = event.dataTransfer.getData('text/plain');
            if (sourcePath && this.internalDrag) {
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('moveItem', sourcePath, destinationPath);
                this.internalDrag = false;
            }
        },

        confirmDelete(path, isDir) {
            this.itemToDelete = path;
            this.isDirToDelete = isDir;
            this.openDeleteModal = true;
        },
        
        executeDelete() {
            if (this.itemToDelete) {
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('deleteItem', this.itemToDelete, this.isDirToDelete);
                this.openDeleteModal = false;
                this.itemToDelete = null;
            }
        }
    }" 
    @dragover.prevent="if (!internalDrag) dropping = true" 
    @dragleave.prevent="dropping = false" 
    @drop.prevent="handleDropOnMain($event)"
    class="relative min-h-[500px] bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden"
>
    <!-- Overlay de Drop -->
    <div x-show="dropping" x-cloak class="absolute inset-0 z-50 flex items-center justify-center bg-indigo-50/90 dark:bg-indigo-900/90 border-4 border-dashed border-indigo-500 rounded-xl transition-all">
        <div class="text-center">
            <i class="fi fi-rr-cloud-upload-alt text-6xl text-indigo-600 dark:text-indigo-400"></i>
            <h3 class="mt-4 text-2xl font-bold text-indigo-900 dark:text-indigo-100">Solte os arquivos aqui</h3>
            <p class="text-indigo-700 dark:text-indigo-300">Eles serão enviados automaticamente.</p>
        </div>
    </div>

    <!-- Header do File Manager -->
    <div class="flex flex-col sm:flex-row items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
        
        <!-- Breadcrumbs & Raiz -->
        <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
            <!-- Root Switcher -->
            <div class="flex items-center p-1 bg-gray-100 dark:bg-gray-700/50 rounded-lg">
                <button wire:click="switchRoot('images')" 
                        class="px-3 py-1 text-xs font-bold rounded-md transition-all <?php echo e($baseDirectory === 'images' ? 'bg-white dark:bg-gray-800 text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'); ?>">
                    <i class="fi fi-rr-gallery mr-1"></i> Galeria
                </button>
                <button wire:click="switchRoot('storage')" 
                        class="px-3 py-1 text-xs font-bold rounded-md transition-all <?php echo e($baseDirectory === 'storage' ? 'bg-white dark:bg-gray-800 text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'); ?>">
                    <i class="fi fi-rr-hdd mr-1"></i> Arquivos
                </button>
            </div>

            <!-- Breadcrumbs Navigation -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 font-medium overflow-x-auto whitespace-nowrap pb-2 sm:pb-0 hide-scrollbar w-full sm:w-auto">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($this->breadcrumbs) > 1): ?>
                <button wire:click="upDirectory" 
                        @dragover.prevent.stop="$el.classList.add('bg-gray-300', 'dark:bg-gray-600')"
                        @dragleave.prevent.stop="$el.classList.remove('bg-gray-300', 'dark:bg-gray-600')"
                        @drop.prevent.stop="$el.classList.remove('bg-gray-300', 'dark:bg-gray-600'); handleDropOnTarget($event, '<?php echo e($this->breadcrumbs[count($this->breadcrumbs)-2]['path']); ?>')"
                        class="p-1 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <i class="fi fi-rr-arrow-left mt-1"></i>
                </button>
                <div class="h-4 w-px bg-gray-300 dark:bg-gray-600 mx-1"></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $crumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->last): ?>
                    <button wire:click="navigateTo('<?php echo e($crumb['path']); ?>')" 
                            @dragover.prevent.stop="$el.classList.add('underline', 'text-indigo-600', 'dark:text-indigo-400')"
                            @dragleave.prevent.stop="$el.classList.remove('underline', 'text-indigo-600', 'dark:text-indigo-400')"
                            @drop.prevent.stop="$el.classList.remove('underline', 'text-indigo-600', 'dark:text-indigo-400'); handleDropOnTarget($event, '<?php echo e($crumb['path']); ?>')"
                            class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                        <?php echo e($crumb['name']); ?>

                    </button>
                    <i class="fi fi-rr-angle-small-right text-gray-400 text-xs"></i>
                    <?php else: ?>
                    <span class="text-gray-900 dark:text-gray-100"><?php echo e($crumb['name']); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </nav>
        </div>

        <!-- Ações -->
        <div class="flex items-center space-x-3 w-full sm:w-auto mt-2 sm:mt-0">
            <!-- Loading Indicator -->
            <div wire:loading wire:target="uploads" class="text-sm text-indigo-600 dark:text-indigo-400 font-medium flex items-center">
                <i class="fi fi-rr-spinner animate-spin mr-2"></i> Enviando...
            </div>
            
            <button wire:click="$set('showCreateModal', true)" class="px-3 py-1.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm flex items-center">
                <i class="fi fi-rr-folder-add mr-2"></i> Nova Pasta
            </button>

            <!-- Upload File Input (Hidden) -->
            <label class="px-3 py-1.5 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition shadow-sm cursor-pointer flex items-center">
                <i class="fi fi-rr-upload mr-2"></i> Enviar
                <input type="file" multiple wire:model="uploads" class="hidden">
            </label>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="p-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($directories) && empty($files)): ?>
            <div class="flex flex-col items-center justify-center py-20 text-gray-500 dark:text-gray-400">
                <i class="fi fi-rr-folder-open text-5xl mb-4 text-gray-300 dark:text-gray-600"></i>
                <p>Esta pasta está vazia.</p>
                <p class="text-sm mt-1">Arraste arquivos para cá ou clique em Enviar.</p>
            </div>
        <?php else: ?>
            <!-- Grid Unificado -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">

                <!-- Pastas -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $directories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div draggable="true"
                     @dragstart="startDrag($event, '<?php echo e($dir['path']); ?>')"
                     @dragover.prevent.stop="$event.currentTarget.querySelector('.card-inner').classList.add('ring-2','ring-indigo-400','!bg-indigo-100')"
                     @dragleave.prevent.stop="$event.currentTarget.querySelector('.card-inner').classList.remove('ring-2','ring-indigo-400','!bg-indigo-100')"
                     @drop.prevent.stop="$event.currentTarget.querySelector('.card-inner').classList.remove('ring-2','ring-indigo-400','!bg-indigo-100'); handleDropOnTarget($event, '<?php echo e($dir['path']); ?>')"
                     wire:click="enterDirectory('<?php echo e($dir['name']); ?>')"
                     class="group relative flex flex-col cursor-pointer select-none">

                    <!-- Botão Deletar -->
                    <button @click.stop="confirmDelete('<?php echo e($dir['path']); ?>', true)"
                            class="absolute top-1 right-1 z-10 p-1.5 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-all shadow">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>

                    <!-- Preview Container 1:1 -->
                    <div class="w-full relative pb-[100%]">
                        <div class="card-inner absolute inset-0 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/60 flex items-center justify-center overflow-hidden transition-all group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/50 group-hover:shadow-md">
                            <svg class="w-1/2 h-1/2 text-indigo-400 dark:text-indigo-500 group-hover:scale-110 transition-transform duration-200" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20 5h-8.586L9.707 3.293A1 1 0 009 3H4a2 2 0 00-2 2v14a2 2 0 002 2h16a2 2 0 002-2V7a2 2 0 00-2-2z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Rodapé -->
                    <div class="mt-2 px-0.5">
                        <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate leading-tight" title="<?php echo e($dir['name']); ?>"><?php echo e($dir['name']); ?></p>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Pasta</p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Arquivos -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div draggable="true"
                     @dragstart="startDrag($event, '<?php echo e($file['path']); ?>')"
                     class="group relative flex flex-col select-none">

                    <!-- Botão Deletar -->
                    <button @click.stop="confirmDelete('<?php echo e($file['path']); ?>', false)"
                            class="absolute top-1 right-1 z-10 p-1.5 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-all shadow">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>

                    <!-- Preview Container 1:1 -->
                    <div class="w-full relative pb-[100%]">
                        <div class="absolute inset-0">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pickerMode && $file['is_image']): ?>
                            <button type="button"
                                    wire:click.prevent="selectMedia('<?php echo e($file['path']); ?>', '<?php echo e($file['url']); ?>')"
                                    draggable="false" @dragstart.prevent
                                    title="Selecionar"
                                    class="w-full h-full rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 relative group/pick block bg-gray-50 dark:bg-gray-900">
                                <img src="<?php echo e($file['url']); ?>" alt="<?php echo e($file['name']); ?>" loading="lazy" draggable="false"
                                     class="object-cover w-full h-full group-hover/pick:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 bg-indigo-600/0 group-hover/pick:bg-indigo-600/40 transition-colors flex items-center justify-center">
                                    <div class="w-10 h-10 bg-white text-indigo-600 rounded-full flex items-center justify-center opacity-0 group-hover/pick:opacity-100 scale-50 group-hover/pick:scale-100 transition-all shadow-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>
                            </button>
                            <?php elseif($file['is_image']): ?>
                            <a href="<?php echo e($file['url']); ?>" target="_blank" draggable="false" @dragstart.prevent
                               class="w-full h-full rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 block group-hover:shadow-md transition-shadow bg-gray-50 dark:bg-gray-900">
                                <img src="<?php echo e($file['url']); ?>" alt="<?php echo e($file['name']); ?>" loading="lazy" draggable="false"
                                     class="object-cover w-full h-full hover:scale-105 transition-transform duration-300">
                            </a>
                            <?php else: ?>
                            <a href="<?php echo e($file['url']); ?>" target="_blank" draggable="false" @dragstart.prevent
                               class="w-full h-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80 flex flex-col items-center justify-center gap-2 group-hover:shadow-md transition-shadow">
                                <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-[11px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400"><?php echo e($file['extension']); ?></span>
                            </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <!-- Rodapé -->
                    <div class="mt-2 px-0.5">
                        <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate leading-tight" title="<?php echo e($file['name']); ?>"><?php echo e($file['name']); ?></p>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight"><?php echo e($file['size']); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>


        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Modal: Criar Pasta -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showCreateModal): ?>
    <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-6 w-full max-w-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Nova Pasta</h3>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome da Pasta</label>
                <input type="text" wire:model.defer="newDirectoryName" wire:keydown.enter="createDirectory" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="ex: banners_promocionais" autofocus>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['newDirectoryName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button wire:click="$set('showCreateModal', false)" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition">Cancelar</button>
                <button wire:click="createDirectory" class="px-4 py-2 text-sm text-white bg-indigo-600 hover:bg-indigo-700 rounded-md transition pointer-events-auto">Criar Pasta</button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Modal: Confirmação de Exclusão (AlpineJS) -->
    <div x-show="openDeleteModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div @click.away="openDeleteModal = false" class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-6 w-full max-w-md border border-gray-200 dark:border-gray-700">
            <!-- Ícone + Título -->
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center mr-4"
                     :class="isDirToDelete ? 'bg-orange-100 dark:bg-orange-900/40' : 'bg-red-100 dark:bg-red-900/40'">
                    <i class="fi fi-rr-trash text-xl"
                       :class="isDirToDelete ? 'text-orange-600 dark:text-orange-400' : 'text-red-600 dark:text-red-400'"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white"
                        x-text="isDirToDelete ? 'Excluir Pasta?' : 'Excluir Arquivo?'"></h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Esta ação não pode ser desfeita.</p>
                </div>
            </div>

            <!-- Aviso extra para pasta -->
            <div x-show="isDirToDelete" class="mb-4 p-4 rounded-lg bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800">
                <div class="flex items-start">
                    <i class="fi fi-rr-exclamation text-orange-500 dark:text-orange-400 text-lg mr-2 mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="text-sm font-semibold text-orange-800 dark:text-orange-300">Atenção: a pasta e TODO o seu conteúdo serão apagados!</p>
                        <p class="text-xs text-orange-700 dark:text-orange-400 mt-1">Todos os arquivos e subpastas dentro dela serão removidos permanentemente do servidor.</p>
                    </div>
                </div>
            </div>

            <!-- Mensagem para arquivo -->
            <p x-show="!isDirToDelete" class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                O arquivo será excluído permanentemente e não poderá ser recuperado.
            </p>

            <!-- Ações -->
            <div class="flex justify-end space-x-3 mt-2">
                <button @click="openDeleteModal = false"
                        class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition">
                    Cancelar
                </button>
                <button @click="executeDelete()"
                        class="px-4 py-2 text-sm font-medium text-white rounded-md transition"
                        :class="isDirToDelete ? 'bg-orange-600 hover:bg-orange-700' : 'bg-red-600 hover:bg-red-700'">
                    <span x-text="isDirToDelete ? 'Sim, excluir tudo' : 'Sim, excluir'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Douglas\Documents\Projetos\construcaxa\resources\views/livewire/file-manager.blade.php ENDPATH**/ ?>