<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Relatório de Pontos - {{ $employee->user->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .label {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .summary-label {
            font-weight: bold;
        }
        .summary-value {
            font-weight: bold;
        }
        .total {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #000;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-green {
            color: #059669;
        }
        .text-red {
            color: #dc2626;
        }
        .text-indigo {
            color: #4f46e5;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Relatório de Pontos</div>
        <div style="font-size: 14px; color: #666;">Período: {{ $from->format('d/m/Y') }} a {{ $to->format('d/m/Y') }}</div>
    </div>

    <div class="info">
        <div class="info-row">
            <span class="label">Funcionário:</span>
            <span>{{ $employee->user->name }}</span>
        </div>
        @if($employee->cpf)
        <div class="info-row">
            <span class="label">CPF:</span>
            <span>{{ $employee->cpf }}</span>
        </div>
        @endif
        @if($employee->position)
        <div class="info-row">
            <span class="label">Cargo:</span>
            <span>{{ $employee->position }}</span>
        </div>
        @endif
        @if($employee->department)
        <div class="info-row">
            <span class="label">Departamento:</span>
            <span>{{ $employee->department }}</span>
        </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Entrada</th>
                <th>Saída</th>
                <th class="text-right">Horas</th>
            </tr>
        </thead>
        <tbody>
            @php
                $groupedByDate = $attendances->groupBy(function($attendance) {
                    return $attendance->punched_date->format('Y-m-d');
                });
                $dayHours = [];
            @endphp
            @foreach($groupedByDate as $date => $dayAttendances)
                @php
                    $entries = $dayAttendances->where('type', 'entry')->sortBy('punched_at');
                    $exits = $dayAttendances->where('type', 'exit')->sortBy('punched_at');
                    $totalDayHours = 0;
                    $entryTime = null;
                    foreach($dayAttendances->sortBy('punched_at') as $att) {
                        if($att->type === 'entry') {
                            $entryTime = $att->punched_at;
                        } elseif($att->type === 'exit' && $entryTime) {
                            $totalDayHours += $entryTime->diffInMinutes($att->punched_at) / 60;
                            $entryTime = null;
                        }
                    }
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                    <td>
                        @foreach($entries as $entry)
                            {{ $entry->punched_at->format('H:i') }}
                            @if(!$loop->last), @endif
                        @endforeach
                        @if($entries->count() === 0)
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td>
                        @foreach($exits as $exit)
                            {{ $exit->punched_at->format('H:i') }}
                            @if(!$loop->last), @endif
                        @endforeach
                        @if($exits->count() === 0)
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td class="text-right">{{ $totalDayHours > 0 ? number_format($totalDayHours, 2, ',', '.') . 'h' : '-' }}</td>
                </tr>
            @endforeach
            @if($attendances->count() === 0)
            <tr>
                <td colspan="4" class="text-center" style="color: #999;">Nenhum registro no período</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-row">
            <span class="summary-label">Total de Horas Trabalhadas:</span>
            <span class="summary-value">{{ number_format($hoursWorked, 2, ',', '.') }}h</span>
        </div>
        @if($employee->hourly_rate)
        <div class="summary-row">
            <span class="summary-label">Valor por Hora:</span>
            <span class="summary-value">R$ {{ number_format($employee->hourly_rate, 2, ',', '.') }}</span>
        </div>
        @elseif($employee->monthly_salary)
        <div class="summary-row">
            <span class="summary-label">Salário Mensal:</span>
            <span class="summary-value">R$ {{ number_format($employee->monthly_salary, 2, ',', '.') }}</span>
        </div>
        @endif
        <div class="summary-row" style="color: #059669;">
            <span class="summary-label">Valor Bruto:</span>
            <span class="summary-value">R$ {{ number_format($grossAmount, 2, ',', '.') }}</span>
        </div>
        @if($deductions->count() > 0)
        <div class="summary-row" style="color: #dc2626;">
            <span class="summary-label">Total de Descontos:</span>
            <span class="summary-value">R$ {{ number_format($totalDeductions, 2, ',', '.') }}</span>
        </div>
        @endif
        <div class="summary-row total" style="color: #4f46e5; font-size: 14px;">
            <span class="summary-label">VALOR LÍQUIDO A PAGAR:</span>
            <span class="summary-value">R$ {{ number_format($netAmount, 2, ',', '.') }}</span>
        </div>
    </div>

    @if($deductions->count() > 0)
    <div style="margin-top: 30px;">
        <h3 style="font-size: 14px; font-weight: bold; margin-bottom: 10px;">Descontos Aplicados</h3>
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th class="text-right">Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($deductions as $deduction)
                <tr>
                    <td>{{ $deduction->date->format('d/m/Y') }}</td>
                    <td>{{ $deduction->description }}</td>
                    <td class="text-right">R$ {{ number_format($deduction->amount, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Relatório gerado em {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>



