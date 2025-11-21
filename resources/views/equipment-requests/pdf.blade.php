<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Requisição de Equipamento #{{ $equipmentRequest->number }}</title>
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
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
        }
        .signature-line {
            display: inline-block;
            width: 250px;
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }
        .signature-name {
            font-weight: bold;
        }
        .signature-role {
            font-size: 10px;
            color: #666;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-approved { background-color: #dbeafe; color: #1e40af; }
        .status-rejected { background-color: #fecaca; color: #991b1b; }
        .status-completed { background-color: #d1fae5; color: #065f46; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Requisição de Equipamento</div>
        <div>Número: {{ $equipmentRequest->number }}</div>
    </div>

    <div class="info">
        <div class="info-row">
            <span class="label">Data:</span>
            <span>{{ $equipmentRequest->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="label">Tipo:</span>
            <span>{{ $equipmentRequest->type === 'loan' ? 'Empréstimo' : 'Devolução' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Status:</span>
            <span class="status-badge status-{{ $equipmentRequest->status }}">
                @switch($equipmentRequest->status)
                    @case('pending') Pendente @break
                    @case('approved') Aprovado @break
                    @case('rejected') Rejeitado @break
                    @case('completed') Concluído @break
                @endswitch
            </span>
        </div>
    </div>

    <div class="info">
        <div class="info-row">
            <span class="label">Funcionário Requisitante:</span>
            <span>{{ $equipmentRequest->employee->name }} - {{ $equipmentRequest->employee->department }}</span>
        </div>
        @if($equipmentRequest->employee->cpf)
            <div class="info-row">
                <span class="label">CPF:</span>
                <span>{{ $equipmentRequest->employee->cpf }}</span>
            </div>
        @endif
        @if($equipmentRequest->employee->phone)
            <div class="info-row">
                <span class="label">WhatsApp:</span>
                <span>{{ $equipmentRequest->employee->phone }}</span>
            </div>
        @elseif($equipmentRequest->employee->user->phone)
            <div class="info-row">
                <span class="label">WhatsApp:</span>
                <span>{{ $equipmentRequest->employee->user->phone }}</span>
            </div>
        @endif
        @if($equipmentRequest->serviceOrder)
            <div class="info-row">
                <span class="label">Ordem de Serviço:</span>
                <span>{{ $equipmentRequest->serviceOrder->number }} - {{ $equipmentRequest->serviceOrder->client_name }}</span>
            </div>
        @endif
    </div>

    @if($equipmentRequest->expected_return_date)
    <div class="info">
        <div class="info-row">
            <span class="label">Data Prevista de Devolução:</span>
            <span>{{ $equipmentRequest->expected_return_date->format('d/m/Y') }}</span>
        </div>
    </div>
    @endif

    @if($equipmentRequest->purpose)
    <div class="info">
        <div class="label">Finalidade:</div>
        <div>{{ $equipmentRequest->purpose }}</div>
    </div>
    @endif

    @if($equipmentRequest->notes)
        <div class="info">
            <div class="label">Observações:</div>
            <div>{{ $equipmentRequest->notes }}</div>
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Equipamento</th>
                <th>Número de Série</th>
                <th>Quantidade</th>
                <th>Observações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($equipmentRequest->items as $item)
                <tr>
                    <td>
                        {{ $item->equipment->name }}
                        @if($item->equipment->category)
                            <br><small>{{ $item->equipment->category->name }}</small>
                        @endif
                    </td>
                    <td>{{ $item->equipment->serial_number }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->condition_notes ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <div class="signature-line">
            <div class="signature-name">{{ $equipmentRequest->employee->name }}</div>
            <div class="signature-role">{{ $equipmentRequest->employee->position }} - {{ $equipmentRequest->employee->department }}</div>
            @if($equipmentRequest->employee->cpf)
                <div class="signature-role">CPF: {{ $equipmentRequest->employee->cpf }}</div>
            @endif
        </div>
    </div>

    <div class="footer">
        Documento gerado em {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>





