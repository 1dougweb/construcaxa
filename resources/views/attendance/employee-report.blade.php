<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Relatório de Pontos - ') . $employee->user->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('attendance.employee.pdf', ['employee' => $employee, 'from' => $from, 'to' => $to]) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('Imprimir PDF') }}
                </a>
                <a href="{{ route('employees.show', $employee) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                    {{ __('Voltar') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <x-label for="from" value="{{ __('De (data)') }}" />
                            <x-input id="from" type="date" name="from" class="mt-1 block w-full" value="{{ $from }}" />
                        </div>
                        <div>
                            <x-label for="to" value="{{ __('Até (data)') }}" />
                            <x-input id="to" type="date" name="to" class="mt-1 block w-full" value="{{ $to }}" />
                        </div>
                        <div class="flex items-end">
                            <x-button type="submit" class="w-full">{{ __('Filtrar') }}</x-button>
                        </div>
                        <div class="flex items-end">
                            <a href="{{ route('attendance.employee.report', $employee) }}" class="w-full text-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                {{ __('Limpar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resumo -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">{{ __('Horas Trabalhadas') }}</div>
                        <div class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($hoursWorked, 2, ',', '.') }}h</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">{{ __('Valor Bruto') }}</div>
                        <div class="mt-2 text-2xl font-semibold text-green-600">R$ {{ number_format($grossAmount, 2, ',', '.') }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">{{ __('Descontos') }}</div>
                        <div class="mt-2 text-2xl font-semibold text-red-600">R$ {{ number_format($totalDeductions, 2, ',', '.') }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-2 border-indigo-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">{{ __('Valor Líquido') }}</div>
                        <div class="mt-2 text-2xl font-semibold text-indigo-600">R$ {{ number_format($netAmount, 2, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <!-- Tabela de Pontos -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Registros de Ponto') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700">{{ __('Data') }}</th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700">{{ __('Hora') }}</th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700">{{ __('Tipo') }}</th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700">{{ __('Horas do Dia') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @php
                                    $groupedByDate = $attendances->groupBy(function($attendance) {
                                        return $attendance->punched_date->format('Y-m-d');
                                    });
                                    $dayHours = [];
                                    $entryTime = null;
                                    foreach($attendances->sortBy('punched_at') as $att) {
                                        if($att->type === 'entry') {
                                            $entryTime = $att->punched_at;
                                        } elseif($att->type === 'exit' && $entryTime) {
                                            $dateKey = $att->punched_date->format('Y-m-d');
                                            $hours = $entryTime->diffInMinutes($att->punched_at) / 60;
                                            $dayHours[$dateKey] = ($dayHours[$dateKey] ?? 0) + $hours;
                                            $entryTime = null;
                                        }
                                    }
                                @endphp
                                @forelse($groupedByDate as $date => $dayAttendances)
                                    @php
                                        $entries = $dayAttendances->where('type', 'entry')->sortBy('punched_at');
                                        $exits = $dayAttendances->where('type', 'exit')->sortBy('punched_at');
                                        $dayTotalHours = $dayHours[$date] ?? 0;
                                    @endphp
                                    <tr>
                                        <td class="px-3 py-2">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                                        <td class="px-3 py-2">
                                            @foreach($entries as $entry)
                                                <span class="text-green-600">{{ $entry->punched_at->format('H:i') }}</span>
                                                @if(!$loop->last), @endif
                                            @endforeach
                                            @if($entries->count() > 0 && $exits->count() > 0) <span class="mx-1">-</span> @endif
                                            @foreach($exits as $exit)
                                                <span class="text-indigo-600">{{ $exit->punched_at->format('H:i') }}</span>
                                                @if(!$loop->last), @endif
                                            @endforeach
                                            @if($entries->count() === 0 && $exits->count() === 0)
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2">
                                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $entries->count() > 0 ? 'Entrada' : '' }}{{ $entries->count() > 0 && $exits->count() > 0 ? ' / ' : '' }}{{ $exits->count() > 0 ? 'Saída' : '' }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 font-medium">
                                            {{ $dayTotalHours > 0 ? number_format($dayTotalHours, 2, ',', '.') . 'h' : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-3 py-6 text-center text-gray-500">{{ __('Sem registros no período') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Descontos -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Descontos Aplicados') }}</h3>
                        @can('edit employees')
                        <button type="button" onclick="document.getElementById('deduction-modal').classList.remove('hidden')" class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Adicionar Desconto
                        </button>
                        @endcan
                    </div>
                    @if($deductions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700">{{ __('Data') }}</th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700">{{ __('Descrição') }}</th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700">{{ __('Valor') }}</th>
                                    @can('edit employees')
                                    <th class="px-3 py-2 text-left font-medium text-gray-700">{{ __('Ações') }}</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($deductions as $deduction)
                                <tr>
                                    <td class="px-3 py-2">{{ $deduction->date->format('d/m/Y') }}</td>
                                    <td class="px-3 py-2">{{ $deduction->description }}</td>
                                    <td class="px-3 py-2 text-red-600 font-medium">R$ {{ number_format($deduction->amount, 2, ',', '.') }}</td>
                                    @can('edit employees')
                                    <td class="px-3 py-2">
                                        <form method="POST" action="{{ route('employees.deductions.destroy', ['employee' => $employee, 'deduction' => $deduction]) }}" onsubmit="return confirm('Tem certeza que deseja excluir este desconto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                    @endcan
                                </tr>
                                @endforeach
                                <tr class="bg-gray-50 font-semibold">
                                    <td colspan="{{ auth()->user()->can('edit employees') ? '3' : '2' }}" class="px-3 py-2 text-right">{{ __('Total de Descontos') }}</td>
                                    <td class="px-3 py-2 text-red-600">R$ {{ number_format($totalDeductions, 2, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-sm text-gray-500 text-center py-8">{{ __('Nenhum desconto aplicado no período.') }}</p>
                    @endif
                </div>
            </div>

            <!-- Modal de Adicionar Desconto -->
            <div id="deduction-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color: rgba(0, 0, 0, 0.75);">
                <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-md mx-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Adicionar Desconto</h3>
                        <button type="button" onclick="document.getElementById('deduction-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('employees.deductions.store', $employee) }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <x-label for="description" value="{{ __('Descrição') }}" />
                                <x-input id="description" type="text" class="mt-1 block w-full" name="description" required />
                            </div>
                            <div>
                                <x-label for="amount" value="{{ __('Valor') }}" />
                                <x-input id="amount" type="number" step="0.01" min="0.01" class="mt-1 block w-full" name="amount" required />
                            </div>
                            <div>
                                <x-label for="date" value="{{ __('Data') }}" />
                                <x-input id="date" type="date" class="mt-1 block w-full" name="date" value="{{ date('Y-m-d') }}" required />
                            </div>
                            <div class="flex gap-2">
                                <x-button type="submit" class="flex-1">{{ __('Adicionar') }}</x-button>
                                <button type="button" onclick="document.getElementById('deduction-modal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                    {{ __('Cancelar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

