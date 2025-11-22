<div>
    <div class="mb-4 bg-white rounded-lg shadow-lx p-8">
        <div class="flex flex-wrap gap-2">
            <!-- Busca -->
            <div class="flex-1 min-w-[200px] flex gap-2">
                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['type' => 'text','class' => 'w-full','wire:model' => 'searchTerm','wire:keydown.enter' => 'doSearch','placeholder' => 'Buscar por nome, SKU ou descrição...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','class' => 'w-full','wire:model' => 'searchTerm','wire:keydown.enter' => 'doSearch','placeholder' => 'Buscar por nome, SKU ou descrição...']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['wire:click' => 'doSearch']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'doSearch']); ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
            </div>

            <!-- Categoria -->
            <div>
                <select wire:model="category" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todas as categorias</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>

            <!-- Fornecedor -->
            <div>
                <select wire:model="supplier" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Todos os fornecedores</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sup->id); ?>"><?php echo e($sup->company_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>
        </div>

        <!-- Filtros de Estoque -->
        <div class="mt-4">
            <div class="text-sm font-medium text-gray-700 mb-2">Status do Estoque</div>
            <div class="flex flex-wrap gap-2">
                <button wire:click="toggleStockFilter('all')" 
                    class="px-4 py-2 text-sm rounded-md <?php echo e(!$lowStock && !$outOfStock ? 'bg-indigo-100 text-indigo-700' : 'bg-white text-gray-700'); ?> border hover:bg-indigo-50">
                    Todos
                </button>
                <button wire:click="toggleStockFilter('low')" 
                    class="px-4 py-2 text-sm rounded-md <?php echo e($lowStock && !$outOfStock ? 'bg-yellow-100 text-yellow-700' : 'bg-white text-gray-700'); ?> border hover:bg-yellow-50">
                    Estoque Baixo
                </button>
                <button wire:click="toggleStockFilter('out')" 
                    class="px-4 py-2 text-sm rounded-md <?php echo e($outOfStock && !$lowStock ? 'bg-red-100 text-red-700' : 'bg-white text-gray-700'); ?> border hover:bg-red-50">
                    Sem Estoque
                </button>
                <button wire:click="toggleStockFilter('both')" 
                    class="px-4 py-2 text-sm rounded-md <?php echo e($lowStock && $outOfStock ? 'bg-orange-100 text-orange-700' : 'bg-white text-gray-700'); ?> border hover:bg-orange-50">
                    Baixo + Sem Estoque
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <div class="flex gap-2">
                    <!--[if BLOCK]><![endif]--><?php if($searchTerm): ?>
                        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['wire:click' => 'clearSearch','class' => 'bg-gray-600 hover:bg-gray-700']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'clearSearch','class' => 'bg-gray-600 hover:bg-gray-700']); ?>
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Voltar
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create products')): ?>
                <a href="<?php echo e(route('products.create')); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <?php echo e(__('Novo Produto')); ?>

                </a>
                <?php endif; ?>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Foto
                            </th>
                            <th wire:click="sortBy('sku')" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                SKU
                                <!--[if BLOCK]><![endif]--><?php if($sortField === 'sku'): ?>
                                    <!--[if BLOCK]><![endif]--><?php if($sortDirection === 'asc'): ?>
                                        &#8593;
                                    <?php else: ?>
                                        &#8595;
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </th>
                            <th wire:click="sortBy('name')" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Nome
                                <!--[if BLOCK]><![endif]--><?php if($sortField === 'name'): ?>
                                    <!--[if BLOCK]><![endif]--><?php if($sortDirection === 'asc'): ?>
                                        &#8593;
                                    <?php else: ?>
                                        &#8595;
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Categoria
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fornecedor
                            </th>
                            <th wire:click="sortBy('stock')" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Estoque
                                <!--[if BLOCK]><![endif]--><?php if($sortField === 'stock'): ?>
                                    <!--[if BLOCK]><![endif]--><?php if($sortDirection === 'asc'): ?>
                                        &#8593;
                                    <?php else: ?>
                                        &#8595;
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </th>
                            <th wire:click="sortBy('price')" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Preço
                                <!--[if BLOCK]><![endif]--><?php if($sortField === 'price'): ?>
                                    <!--[if BLOCK]><![endif]--><?php if($sortDirection === 'asc'): ?>
                                        &#8593;
                                    <?php else: ?>
                                        &#8595;
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </th>
                            <!--[if BLOCK]><![endif]--><?php if(auth()->user()->can('edit products') || auth()->user()->can('delete products')): ?>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <!--[if BLOCK]><![endif]--><?php if($product->photos && is_array($product->photos) && count($product->photos) > 0): ?>
                                        <div x-data="{ 
                                            open: false, 
                                            currentIndex: 0,
                                            images: <?php echo \Illuminate\Support\Js::from(array_map(function($photo) { return asset('storage/' . $photo); }, $product->photos ?? []))->toHtml() ?>,
                                            openLightbox(index) {
                                                this.currentIndex = index;
                                                this.open = true;
                                                document.body.style.overflow = 'hidden';
                                            },
                                            closeLightbox() {
                                                this.open = false;
                                                document.body.style.overflow = '';
                                            },
                                            nextImage() {
                                                this.currentIndex = (this.currentIndex + 1) % this.images.length;
                                            },
                                            prevImage() {
                                                this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                                            }
                                        }" @keydown.escape="closeLightbox()" @keydown.arrow-left="prevImage()" @keydown.arrow-right="nextImage()">
                                            <div class="w-16 h-16 rounded overflow-hidden cursor-pointer hover:opacity-80 transition-opacity">
                                                <img 
                                                    src="<?php echo e(asset('storage/' . $product->photos[0])); ?>" 
                                                    alt="<?php echo e($product->name); ?>"
                                                    class="w-full h-full object-cover border border-gray-200 rounded-md"
                                                    @click="openLightbox(0)"
                                                >
                                            </div>
                                            
                                            <!-- Lightbox -->
                                            <div 
                                                x-show="open"
                                                x-cloak
                                                @click.self="closeLightbox()"
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-in duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                            >
                                                <!-- Botão Fechar (redondo) -->
                                                <button 
                                                    @click="closeLightbox()"
                                                    class="absolute top-4 right-4 w-12 h-12 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white flex items-center justify-center transition-all duration-200 z-10"
                                                >
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                                
                                                <!-- Botão Anterior (redondo) -->
                                                <button 
                                                    @click="prevImage()"
                                                    x-show="images.length > 1"
                                                    class="absolute left-4 w-12 h-12 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white flex items-center justify-center transition-all duration-200 z-10"
                                                >
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                    </svg>
                                                </button>
                                                
                                                <!-- Botão Próximo (redondo) -->
                                                <button 
                                                    @click="nextImage()"
                                                    x-show="images.length > 1"
                                                    class="absolute right-4 w-12 h-12 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white flex items-center justify-center transition-all duration-200 z-10"
                                                >
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </button>
                                                
                                                <!-- Imagem -->
                                                <div 
                                                    class="max-w-7xl max-h-[90vh] mx-4"
                                                    x-transition:enter="transition ease-out duration-300"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-200"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="opacity-0 scale-95"
                                                >
                                                    <img 
                                                        :src="images[currentIndex]" 
                                                        :alt="'<?php echo e($product->name); ?> - ' + (currentIndex + 1)"
                                                        class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl"
                                                    >
                                                </div>
                                                
                                                <!-- Indicador de imagem -->
                                                <div 
                                                    x-show="images.length > 1"
                                                    class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-50 text-white px-4 py-2 rounded-full text-sm"
                                                >
                                                    <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($product->sku); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($product->name); ?></div>
                                    <!--[if BLOCK]><![endif]--><?php if($product->description): ?>
                                        <div class="text-sm text-gray-500"><?php echo e(Str::limit($product->description, 50)); ?></div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($product->category ? $product->category->name : '-'); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($product->supplier ? $product->supplier->company_name : '-'); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <!--[if BLOCK]><![endif]--><?php if($product->stock <= 0): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Sem estoque
                                        </span>
                                    <?php elseif($product->stock <= $product->min_stock): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <?php echo e($product->stock); ?> <?php echo e($product->unit_label); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <?php echo e($product->stock); ?> <?php echo e($product->unit_label); ?>

                                        </span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    R$ <?php echo e(number_format($product->price, 2, ',', '.')); ?>

                                </td>
                                <!--[if BLOCK]><![endif]--><?php if(auth()->user()->can('edit products') || auth()->user()->can('delete products')): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit products')): ?>
                                    <button wire:click="edit(<?php echo e($product->id); ?>)" type="button" class="text-indigo-600 hover:text-indigo-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete products')): ?>
                                    <button wire:click="confirmDelete(<?php echo e($product->id); ?>)" type="button" class="ml-2 text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="<?php echo e((auth()->user()->can('edit products') || auth()->user()->can('delete products')) ? '8' : '7'); ?>" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Nenhum produto encontrado.
                                </td>
                            </tr>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <?php echo e($products->links()); ?>

            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <!--[if BLOCK]><![endif]--><?php if($productToDelete): ?>
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Confirmar exclusão
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Tem certeza que deseja excluir este produto? Esta ação não pode ser desfeita.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="delete" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Excluir
                    </button>
                    <button wire:click="cancelDelete" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/livewire/product-list.blade.php ENDPATH**/ ?>