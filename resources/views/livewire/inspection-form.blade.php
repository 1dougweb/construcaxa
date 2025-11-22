<div>
    <div class="bg-white shadow rounded-md p-6">
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cliente -->
                <div class="col-span-2">
                    <x-label for="client_id" value="{{ __('Cliente') }} *" />
                    <x-select id="client_id" class="mt-1 block w-full" wire:model="client_id" required>
                        <option value="">Selecione um cliente</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="client_id" class="mt-2" />
                </div>

                <!-- Data da Vistoria -->
                <div>
                    <x-label for="inspection_date" value="{{ __('Data da Vistoria') }} *" />
                    <x-input id="inspection_date" type="date" class="mt-1 block w-full" wire:model="inspection_date" required />
                    <x-input-error for="inspection_date" class="mt-2" />
                </div>

                <!-- Responsável -->
                <div>
                    <x-label for="inspector_id" value="{{ __('Responsável pela Vistoria') }} *" />
                    <x-select id="inspector_id" class="mt-1 block w-full" wire:model="inspector_id" required>
                        <option value="">Selecione um responsável</option>
                        @foreach($inspectors as $inspector)
                            <option value="{{ $inspector->id }}">{{ $inspector->name }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="inspector_id" class="mt-2" />
                </div>

                <!-- Endereço -->
                <div class="col-span-2">
                    <x-label for="address" value="{{ __('Endereço/Local da Vistoria') }}" />
                    <x-textarea id="address" class="mt-1 block w-full" wire:model="address" rows="2" />
                    <x-input-error for="address" class="mt-2" />
                </div>

                <!-- Descrição -->
                <div class="col-span-2">
                    <x-label for="description" value="{{ __('Descrição/Observações') }}" />
                    <x-textarea id="description" class="mt-1 block w-full" wire:model="description" rows="4" />
                    <x-input-error for="description" class="mt-2" />
                </div>

                <!-- Status -->
                <div>
                    <x-label for="status" value="{{ __('Status') }} *" />
                    <x-select id="status" class="mt-1 block w-full" wire:model="status" required>
                        <option value="draft">Rascunho</option>
                        <option value="pending">Pendente</option>
                        <option value="approved">Aprovada</option>
                        <option value="rejected">Rejeitada</option>
                    </x-select>
                    <x-input-error for="status" class="mt-2" />
                </div>

                <!-- Fotos -->
                <div class="col-span-2">
                    <x-label for="tempPhotos" value="{{ __('Fotos') }}" />
                    <input type="file" id="tempPhotos" class="mt-1 block w-full" wire:model="tempPhotos" multiple accept="image/*" />
                    <x-input-error for="tempPhotos" class="mt-2" />
                    <p class="mt-1 text-sm text-gray-500">Você pode selecionar múltiplas fotos</p>
                </div>

                <!-- Fotos Existentes e Temporárias -->
                @if((isset($photos) && count($photos) > 0) || (isset($tempPhotos) && count($tempPhotos) > 0))
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fotos Adicionadas</label>
                    <div class="grid grid-cols-4 gap-4">
                        @if(isset($photos))
                            @foreach($photos as $index => $photo)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $photo) }}" alt="Foto" class="w-full h-32 object-cover rounded">
                                    <button type="button" wire:click="removePhoto({{ $index }})" 
                                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                        @if(isset($tempPhotos))
                            @foreach($tempPhotos as $index => $photo)
                                <div class="relative">
                                    <img src="{{ $photo->temporaryUrl() }}" alt="Foto temporária" class="w-full h-32 object-cover rounded">
                                    <button type="button" wire:click="removePhoto({{ count($photos ?? []) + $index }})" 
                                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @endif

                <!-- Observações -->
                <div class="col-span-2">
                    <x-label for="notes" value="{{ __('Observações Adicionais') }}" />
                    <x-textarea id="notes" class="mt-1 block w-full" wire:model="notes" rows="3" />
                    <x-input-error for="notes" class="mt-2" />
                </div>
            </div>

            <!-- Botões -->
            <div class="mt-6 flex justify-end space-x-2">
                <a href="{{ route('inspections.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Salvar Vistoria
                </button>
            </div>
        </form>
    </div>
</div>
