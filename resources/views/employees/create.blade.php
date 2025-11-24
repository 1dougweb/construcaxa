<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Novo Funcionário') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome -->
                            <div>
                                <x-label for="name" value="{{ __('Nome') }}" />
                                <x-input id="name" type="text" class="mt-1 block w-full" name="name" :value="old('name')" required autofocus />
                                <x-input-error for="name" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input id="email" type="email" class="mt-1 block w-full" name="email" :value="old('email')" required />
                                <x-input-error for="email" class="mt-2" />
                            </div>

                            <!-- Senha -->
                            <div>
                                <x-label for="password" value="{{ __('Senha') }}" />
                                <x-input id="password" type="password" class="mt-1 block w-full" name="password" required />
                                <x-input-error for="password" class="mt-2" />
                            </div>

                            <!-- Confirmar Senha -->
                            <div>
                                <x-label for="password_confirmation" value="{{ __('Confirmar Senha') }}" />
                                <x-input id="password_confirmation" type="password" class="mt-1 block w-full" name="password_confirmation" required />
                                <x-input-error for="password_confirmation" class="mt-2" />
                            </div>

                            <!-- Cargo -->
                            <div>
                                <x-label for="position" value="{{ __('Cargo') }}" />
                                <x-input id="position" type="text" class="mt-1 block w-full" name="position" :value="old('position')" required />
                                <x-input-error for="position" class="mt-2" />
                            </div>

                            <!-- Departamento -->
                            <div>
                                <x-label for="department" value="{{ __('Departamento') }}" />
                                <x-input id="department" type="text" class="mt-1 block w-full" name="department" :value="old('department')" required />
                                <x-input-error for="department" class="mt-2" />
                            </div>

                            <!-- Telefone -->
                            <div>
                                <x-label for="phone" value="{{ __('Telefone') }}" />
                                <x-input id="phone" type="text" class="mt-1 block w-full" name="phone" :value="old('phone')" />
                                <x-input-error for="phone" class="mt-2" />
                            </div>

                            <!-- Endereço -->
                            <div>
                                <x-label for="address" value="{{ __('Endereço') }}" />
                                <x-input id="address" type="text" class="mt-1 block w-full" name="address" :value="old('address')" />
                                <x-input-error for="address" class="mt-2" />
                            </div>

                            <!-- Data de Nascimento -->
                            <div>
                                <x-label for="birth_date" value="{{ __('Data de Nascimento') }}" />
                                <x-input id="birth_date" type="date" class="mt-1 block w-full" name="birth_date" :value="old('birth_date')" />
                                <x-input-error for="birth_date" class="mt-2" />
                            </div>

                            <!-- CPF -->
                            <div>
                                <x-label for="cpf" value="{{ __('CPF') }}" />
                                <x-input id="cpf" type="text" class="mt-1 block w-full" name="cpf" :value="old('cpf')" required />
                                <x-input-error for="cpf" class="mt-2" />
                            </div>

                            <!-- RG -->
                            <div>
                                <x-label for="rg" value="{{ __('RG') }}" />
                                <x-input id="rg" type="text" class="mt-1 block w-full" name="rg" :value="old('rg')" />
                                <x-input-error for="rg" class="mt-2" />
                            </div>

                            <!-- Documento (ID) -->
                            <div>
                                <x-label for="document_id" value="{{ __('Documento (ID)') }}" />
                                <x-input id="document_id" type="text" class="mt-1 block w-full" name="document_id" :value="old('document_id')" />
                                <x-input-error for="document_id" class="mt-2" />
                            </div>

                            <!-- Contato de Emergência -->
                            <div>
                                <x-label for="emergency_contact" value="{{ __('Contato de Emergência') }}" />
                                <x-input id="emergency_contact" type="text" class="mt-1 block w-full" name="emergency_contact" :value="old('emergency_contact')" />
                                <x-input-error for="emergency_contact" class="mt-2" />
                            </div>

                            <!-- Foto de Perfil -->
                            <div>
                                <x-label for="profile_photo" value="{{ __('Foto de Perfil') }}" />
                                <input id="profile_photo" name="profile_photo" type="file" accept="image/*" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50" />
                                <x-input-error for="profile_photo" class="mt-2" />
                            </div>

                            <!-- Documento (arquivo) -->
                            <div>
                                <x-label for="document_file" value="{{ __('Documento (arquivo)') }}" />
                                <input id="document_file" name="document_file" type="file" accept=".pdf,image/*" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50" />
                                <x-input-error for="document_file" class="mt-2" />
                            </div>

                            <!-- Salário por hora -->
                            <div>
                                <x-label for="hourly_rate" value="{{ __('Valor Hora (R$)') }}" />
                                <x-input id="hourly_rate" type="number" step="0.01" class="mt-1 block w-full" name="hourly_rate" :value="old('hourly_rate')" />
                                <x-input-error for="hourly_rate" class="mt-2" />
                            </div>

                            <!-- Salário mensal -->
                            <div>
                                <x-label for="monthly_salary" value="{{ __('Salário Mensal (R$)') }}" />
                                <x-input id="monthly_salary" type="number" step="0.01" class="mt-1 block w-full" name="monthly_salary" :value="old('monthly_salary')" />
                                <x-input-error for="monthly_salary" class="mt-2" />
                            </div>

                            <!-- Horas diárias esperadas -->
                            <div>
                                <x-label for="expected_daily_hours" value="{{ __('Horas diárias esperadas') }}" />
                                <x-input id="expected_daily_hours" type="number" step="0.25" class="mt-1 block w-full" name="expected_daily_hours" :value="old('expected_daily_hours', 8)" />
                                <x-input-error for="expected_daily_hours" class="mt-2" />
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="mt-6">
                            <x-label for="notes" value="{{ __('Observações') }}" />
                            <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm" rows="3">{{ old('notes') }}</textarea>
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
