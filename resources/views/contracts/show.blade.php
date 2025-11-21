<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes do Contrato') }}
            </h2>
            <div class="flex space-x-2">
                @can('edit contracts')
                <a href="{{ route('contracts.edit', $contract) }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    <i class="bi bi-pencil mr-2"></i>
                    Editar
                </a>
                @endcan
                <a href="{{ route('contracts.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-4">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $contract->contract_number }}</h1>
                        <p class="text-gray-600 mt-1">{{ $contract->title }}</p>
                    </div>
                    <span class="px-3 py-1 text-sm rounded-full {{ $contract->status_color }}">
                        {{ $contract->status_label }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Cliente</h3>
                        <p class="text-gray-900">{{ $contract->client->name }}</p>
                    </div>
                    @if($contract->project)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Projeto</h3>
                        <p class="text-gray-900">{{ $contract->project->name }}</p>
                    </div>
                    @endif
                    @if($contract->value)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Valor</h3>
                        <p class="text-gray-900">{{ $contract->formatted_value }}</p>
                    </div>
                    @endif
                    @if($contract->start_date)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Data de Início</h3>
                        <p class="text-gray-900">{{ $contract->start_date->format('d/m/Y') }}</p>
                    </div>
                    @endif
                    @if($contract->end_date)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Data de Término</h3>
                        <p class="text-gray-900">{{ $contract->end_date->format('d/m/Y') }}</p>
                    </div>
                    @endif
                    @if($contract->signed_at)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Data de Assinatura</h3>
                        <p class="text-gray-900">{{ $contract->signed_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>

                @if($contract->description)
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Descrição</h3>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $contract->description }}</p>
                </div>
                @endif

                @if($contract->notes)
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Observações</h3>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $contract->notes }}</p>
                </div>
                @endif

                @if($contract->file_path)
                <div class="border-t pt-6">
                    <a href="{{ route('contracts.download', $contract) }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <i class="bi bi-download mr-2"></i>
                        Baixar PDF
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>



