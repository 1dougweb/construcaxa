<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Funcionário') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form id="employee-edit-form" action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        @if ($errors->any())
                            <div class="mb-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded">
                                <strong class="font-bold">Erro!</strong>
                                <span class="block sm:inline">Por favor, corrija os seguintes erros:</span>
                                <ul class="mt-2 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Foto destacada e campos nome/email -->
                        <div class="flex gap-6 items-start mb-6">
                            <!-- Foto destacada à esquerda -->
                            <div class="flex-shrink-0">
                                <x-photo-upload-simple 
                                    name="profile_photo"
                                    label="{{ __('Foto de Perfil') }}"
                                    :required="false"
                                    :existingPhotoPath="$employee->profile_photo_path"
                                />
                            </div>
                            
                            <!-- Nome e Email à direita -->
                            <div class="flex-1 space-y-4">
                                <div>
                                    <x-label for="name" value="{{ __('Nome') }}" />
                                    <x-input id="name" type="text" class="mt-1 block w-full" name="name" :value="old('name', $employee->user->name)" required autofocus />
                                    <x-input-error for="name" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="email" value="{{ __('Email') }}" />
                                    <x-input id="email" type="email" class="mt-1 block w-full" name="email" :value="old('email', $employee->user->email)" required />
                                    <x-input-error for="email" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Cargo -->
                            <div>
                                <x-label for="position" value="{{ __('Cargo') }}" />
                                <x-input id="position" type="text" class="mt-1 block w-full" name="position" :value="old('position', $employee->position)" required />
                                <x-input-error for="position" class="mt-2" />
                            </div>

                            <!-- Departamento -->
                            <div>
                                <x-label for="department" value="{{ __('Departamento') }}" />
                                <x-input id="department" type="text" class="mt-1 block w-full" name="department" :value="old('department', $employee->department)" required />
                                <x-input-error for="department" class="mt-2" />
                            </div>

                            <!-- Data de Contratação -->
                            <div>
                                <x-label for="hire_date" value="{{ __('Data de Contratação') }}" />
                                <x-input id="hire_date" type="date" class="mt-1 block w-full" name="hire_date" :value="old('hire_date', optional($employee->hire_date)->format('Y-m-d'))" required />
                                <x-input-error for="hire_date" class="mt-2" />
                            </div>

                            <!-- Telefone -->
                            <div>
                                <x-label for="phone" value="{{ __('Telefone') }}" />
                                <x-input id="phone" type="text" class="mt-1 block w-full mask-phone" name="phone" :value="old('phone', $employee->phone)" />
                                <x-input-error for="phone" class="mt-2" />
                            </div>

                            <!-- Celular -->
                            <div>
                                <x-label for="cellphone" value="{{ __('Celular') }}" />
                                <x-input id="cellphone" type="text" class="mt-1 block w-full mask-cellphone" name="cellphone" :value="old('cellphone', $employee->cellphone)" />
                                <x-input-error for="cellphone" class="mt-2" />
                            </div>

                            <!-- Endereço -->
                            <div>
                                <x-label for="address" value="{{ __('Endereço') }}" />
                                <x-input id="address" type="text" class="mt-1 block w-full" name="address" :value="old('address', $employee->address)" />
                                <x-input-error for="address" class="mt-2" />
                            </div>

                            <!-- Data de Nascimento -->
                            <div>
                                <x-label for="birth_date" value="{{ __('Data de Nascimento') }}" />
                                <x-input id="birth_date" type="date" class="mt-1 block w-full" name="birth_date" :value="old('birth_date', optional($employee->birth_date)->format('Y-m-d'))" />
                                <x-input-error for="birth_date" class="mt-2" />
                            </div>

                            <!-- CPF -->
                            <div>
                                <x-label for="cpf" value="{{ __('CPF') }}" />
                                <x-input id="cpf" type="text" class="mt-1 block w-full mask-cpf" name="cpf" :value="old('cpf', $employee->cpf)" required />
                                <x-input-error for="cpf" class="mt-2" />
                            </div>

                            <!-- RG -->
                            <div>
                                <x-label for="rg" value="{{ __('RG') }}" />
                                <x-input id="rg" type="text" class="mt-1 block w-full mask-rg" name="rg" :value="old('rg', $employee->rg)" />
                                <x-input-error for="rg" class="mt-2" />
                            </div>

                            <!-- CNPJ (MEI) -->
                            <div>
                                <x-label for="cnpj" value="{{ __('CNPJ (MEI)') }}" />
                                <x-input id="cnpj" type="text" class="mt-1 block w-full mask-cnpj" name="cnpj" :value="old('cnpj', $employee->cnpj)" />
                                <x-input-error for="cnpj" class="mt-2" />
                            </div>

                            <!-- Contato de Emergência -->
                            <div>
                                <x-label for="emergency_contact" value="{{ __('Contato de Emergência (Celular)') }}" />
                                <x-input id="emergency_contact" type="text" class="mt-1 block w-full mask-cellphone" name="emergency_contact" :value="old('emergency_contact', $employee->emergency_contact)" />
                                <x-input-error for="emergency_contact" class="mt-2" />
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="mt-6">
                            <x-label for="notes" value="{{ __('Observações') }}" />
                            <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm" rows="3">{{ old('notes', $employee->notes) }}</textarea>
                            <x-input-error for="notes" class="mt-2" />
                        </div>

                        <div class="mt-6 flex justify-end gap-4">
                            <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                                {{ __('Cancelar') }}
                            </a>

                            <x-button-loading variant="primary" type="submit">
                                {{ __('Salvar') }}
                            </x-button-loading>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
