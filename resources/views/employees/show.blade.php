@php
use Illuminate\Support\Facades\Storage;
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Perfil do Funcionário') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('attendance.employee.report', $employee) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('Ver Pontos') }}
                </a>
                <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {{ __('Editar') }}
                </a>
                <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                    {{ __('Voltar') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Coluna Esquerda - Informações do Funcionário -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Card de Informações Básicas -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Informações do Funcionário') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Nome') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $employee->user->name }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $employee->user->email }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Cargo') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $employee->position }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Departamento') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $employee->department }}</p>
                                </div>
                                
                                @if($employee->phone)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Telefone') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $employee->phone }}</p>
                                </div>
                                @endif
                                
                                @if($employee->cpf)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('CPF') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $employee->cpf }}</p>
                                </div>
                                @endif
                                
                                @if($employee->hire_date)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Data de Contratação') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $employee->hire_date->format('d/m/Y') }}</p>
                                </div>
                                @endif
                                
                                @if($employee->hourly_rate)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Valor por Hora') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">R$ {{ number_format($employee->hourly_rate, 2, ',', '.') }}</p>
                                </div>
                                @endif
                                
                                @if($employee->monthly_salary)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Salário Mensal') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">R$ {{ number_format($employee->monthly_salary, 2, ',', '.') }}</p>
                                </div>
                                @endif
                                
                                @if($employee->expected_daily_hours)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Horas Diárias Esperadas') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ number_format($employee->expected_daily_hours, 2, ',', '.') }}h</p>
                                </div>
                                @endif
                            </div>
                            
                            @if($employee->address)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700">{{ __('Endereço') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $employee->address }}</p>
                            </div>
                            @endif
                            
                            @if($employee->notes)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700">{{ __('Observações') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $employee->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Coluna Direita - Foto de Perfil e Fotos -->
                <div class="space-y-6">
                    <!-- Foto de Perfil -->
                    @if($employee->profile_photo_path)
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Foto de Perfil') }}</h3>
                            <div class="flex justify-center">
                                <img src="{{ Storage::url($employee->profile_photo_path) }}" alt="{{ $employee->user->name }}" class="h-48 w-48 rounded-full object-cover">
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Card de Fotos -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Fotos do Funcionário') }}</h3>
                                <button type="button" onclick="document.getElementById('photo-upload').click()" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded hover:bg-indigo-700">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Adicionar
                                </button>
                            </div>
                            
                            <form id="photo-upload-form" action="{{ route('employees.photos.store', $employee) }}" method="POST" enctype="multipart/form-data" class="hidden">
                                @csrf
                                <input type="file" id="photo-upload" name="photo" accept="image/*" onchange="this.form.submit()">
                            </form>
                            
                            @if($employee->photos && count($employee->photos) > 0)
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($employee->photos as $index => $photo)
                                <div class="relative group">
                                    <img src="{{ Storage::url($photo) }}" alt="Foto {{ $index + 1 }}" class="w-full h-32 object-cover rounded-lg">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-opacity rounded-lg flex items-center justify-center">
                                        <button type="button" onclick="deletePhoto({{ $index }})" class="opacity-0 group-hover:opacity-100 bg-red-600 text-white px-3 py-1 rounded text-sm">
                                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-sm text-gray-500 text-center py-8">{{ __('Nenhuma foto adicionada ainda.') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="delete-photo-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
        <input type="hidden" name="photo_index" id="photo-index">
    </form>

    <script>
        function deletePhoto(index) {
            if (confirm('Tem certeza que deseja excluir esta foto?')) {
                document.getElementById('photo-index').value = index;
                document.getElementById('delete-photo-form').action = '{{ route("employees.photos.destroy", $employee) }}';
                document.getElementById('delete-photo-form').submit();
            }
        }
    </script>
</x-app-layout>

