<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Http;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today($user->timezone ?? config('app.timezone'));

        $todayEntries = Attendance::where('user_id', $user->id)
            ->whereDate('punched_at', $today)
            ->orderBy('punched_at')
            ->get();

        $lastToday = $todayEntries->last();
        $nextType = $lastToday?->type === 'entry' ? 'exit' : 'entry';

        $googleMapsApiKey = Setting::get('google_maps_api_key', config('services.google.maps_key', ''));

        return view('attendance.index', [
            'todayEntries' => $todayEntries,
            'nextType' => $nextType,
            'googleMapsApiKey' => $googleMapsApiKey,
        ]);
    }

    public function punch(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'nullable|numeric',
        ]);

        $now = now();
        $todayDate = $now->toDateString();

        $todayEntries = Attendance::where('user_id', $user->id)
            ->whereDate('punched_at', $todayDate)
            ->orderBy('punched_at')
            ->get();

        $last = $todayEntries->last();

        // Determine allowed action: first of day must be entry; second must be exit; more are blocked
        if (!$last) {
            $type = 'entry';
        } elseif ($last->type === 'entry' && $todayEntries->count() === 1) {
            $type = 'exit';
        } else {
            return back()->withErrors(['error' => 'Já registrou entrada e saída hoje.']);
        }

        Attendance::create([
            'user_id' => $user->id,
            'type' => $type,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'accuracy' => $validated['accuracy'] ?? null,
            'punched_at' => $now,
            'punched_date' => $todayDate,
        ]);

        return redirect()->route('attendance.index')->with('success', 'Ponto de ' . ($type === 'entry' ? 'entrada' : 'saída') . ' registrado.');
    }

    public function manage(Request $request)
    {
        $query = Attendance::query()->with('user');

        if ($request->filled('employee')) {
            $query->where('user_id', $request->integer('employee'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }
        if ($request->filled('from')) {
            $query->whereDate('punched_date', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('punched_date', '<=', $request->date('to'));
        }

        $attendances = $query->orderByDesc('punched_at')->paginate(20);
        $attendances->appends($request->query());

        $googleMapsApiKey = Setting::get('google_maps_api_key', config('services.google.maps_key', ''));

        return view('attendance.manage', [
            'attendances' => $attendances,
            'filters' => [
                'employee' => $request->input('employee'),
                'type' => $request->input('type'),
                'from' => $request->input('from'),
                'to' => $request->input('to'),
            ],
            'googleMapsApiKey' => $googleMapsApiKey,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Attendance::query()->with('user');

        if ($request->filled('employee')) {
            $query->where('user_id', $request->integer('employee'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }
        if ($request->filled('from')) {
            $query->whereDate('punched_date', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('punched_date', '<=', $request->date('to'));
        }

        $filename = 'attendance_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Data', 'Hora', 'Tipo', 'Funcionário', 'Latitude', 'Longitude', 'Precisão']);
            $query->orderBy('punched_at')->chunk(1000, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        optional($row->punched_date)->format('Y-m-d'),
                        optional($row->punched_at)->format('H:i:s'),
                        $row->type,
                        optional($row->user)->name,
                        $row->latitude,
                        $row->longitude,
                        $row->accuracy,
                    ]);
                }
            });
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function reverseGeocode(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $lat = (string) $request->query('lat');
        $lng = (string) $request->query('lng');

        $apiKey = Setting::get('google_maps_api_key', config('services.google.maps_key', ''));
        if (!$apiKey) {
            return response()->json([
                'address' => null,
                'mapUrl' => "https://www.google.com/maps?q={$lat},{$lng}",
                'note' => 'Defina a chave da API do Google Maps nas configurações para obter endereço formatado.'
            ]);
        }

        try {
            $url = 'https://maps.googleapis.com/maps/api/geocode/json';
            $resp = Http::timeout(10)->get($url, [
                'latlng' => $lat . ',' . $lng,
                'key' => $apiKey,
                'language' => 'pt-BR',
                'region' => 'BR',
            ]);

            if (!$resp->successful()) {
                \Log::warning('Reverse geocoding failed', [
                    'status' => $resp->status(),
                    'body' => $resp->body()
                ]);
                return response()->json([
                    'address' => null,
                    'mapUrl' => "https://www.google.com/maps?q={$lat},{$lng}",
                ], 200); // Retornar 200 mesmo em erro para não quebrar o frontend
            }

            $data = $resp->json();
            
            if (!isset($data['status']) || $data['status'] !== 'OK' || empty($data['results'] ?? [])) {
                return response()->json([
                    'address' => null,
                    'mapUrl' => "https://www.google.com/maps?q={$lat},{$lng}",
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Reverse geocoding exception', [
                'message' => $e->getMessage(),
                'lat' => $lat,
                'lng' => $lng
            ]);
            return response()->json([
                'address' => null,
                'mapUrl' => "https://www.google.com/maps?q={$lat},{$lng}",
            ]);
        }

        $result = $data['results'][0];
        $formatted = $result['formatted_address'] ?? null;
        
        // Extrair componentes do endereço
        $components = [];
        foreach ($result['address_components'] ?? [] as $component) {
            $types = $component['types'] ?? [];
            if (in_array('street_number', $types)) {
                $components['street_number'] = $component['long_name'];
            }
            if (in_array('route', $types)) {
                $components['route'] = $component['long_name'];
            }
            if (in_array('sublocality', $types) || in_array('sublocality_level_1', $types)) {
                $components['sublocality'] = $component['long_name'];
            }
            if (in_array('locality', $types)) {
                $components['city'] = $component['long_name'];
            }
            if (in_array('administrative_area_level_1', $types)) {
                $components['state'] = $component['short_name'];
            }
            if (in_array('postal_code', $types)) {
                $components['postal_code'] = $component['long_name'];
            }
            if (in_array('country', $types)) {
                $components['country'] = $component['long_name'];
            }
        }

        return response()->json([
            'address' => $formatted,
            'mapUrl' => "https://www.google.com/maps?q={$lat},{$lng}",
            'components' => $components,
            'location' => [
                'lat' => (float) $lat,
                'lng' => (float) $lng,
            ],
        ]);
    }

    public function employeeReport(Request $request, Employee $employee)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->endOfMonth()->toDateString());

        $fromDate = \Carbon\Carbon::parse($from);
        $toDate = \Carbon\Carbon::parse($to)->endOfDay();

        // Carregar todos os pontos do funcionário no período
        $attendances = Attendance::where('user_id', $employee->user_id)
            ->whereBetween('punched_at', [$fromDate, $toDate])
            ->orderBy('punched_at')
            ->get();

        // Calcular horas trabalhadas
        $hoursWorked = $employee->calculateWorkedHours($fromDate, $toDate);

        // Calcular valor bruto
        $grossAmount = $employee->calculatePaymentAmount($hoursWorked, $fromDate, $toDate);

        // Calcular descontos
        $deductions = $employee->deductions()
            ->whereBetween('date', [$fromDate, $toDate])
            ->get();
        $totalDeductions = $deductions->sum('amount');

        // Valor líquido
        $netAmount = $grossAmount - $totalDeductions;

        return view('attendance.employee-report', [
            'employee' => $employee,
            'attendances' => $attendances,
            'from' => $from,
            'to' => $to,
            'hoursWorked' => $hoursWorked,
            'grossAmount' => $grossAmount,
            'deductions' => $deductions,
            'totalDeductions' => $totalDeductions,
            'netAmount' => $netAmount,
        ]);
    }

    public function generateEmployeePDF(Request $request, Employee $employee)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->endOfMonth()->toDateString());

        $fromDate = \Carbon\Carbon::parse($from);
        $toDate = \Carbon\Carbon::parse($to)->endOfDay();

        // Carregar todos os pontos do funcionário no período
        $attendances = Attendance::where('user_id', $employee->user_id)
            ->whereBetween('punched_at', [$fromDate, $toDate])
            ->orderBy('punched_at')
            ->get();

        // Calcular horas trabalhadas
        $hoursWorked = $employee->calculateWorkedHours($fromDate, $toDate);

        // Calcular valor bruto
        $grossAmount = $employee->calculatePaymentAmount($hoursWorked, $fromDate, $toDate);

        // Calcular descontos
        $deductions = $employee->deductions()
            ->whereBetween('date', [$fromDate, $toDate])
            ->get();
        $totalDeductions = $deductions->sum('amount');

        // Valor líquido
        $netAmount = $grossAmount - $totalDeductions;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('attendance.report-pdf', [
            'employee' => $employee,
            'attendances' => $attendances,
            'from' => $fromDate,
            'to' => $toDate,
            'hoursWorked' => $hoursWorked,
            'grossAmount' => $grossAmount,
            'deductions' => $deductions,
            'totalDeductions' => $totalDeductions,
            'netAmount' => $netAmount,
        ]);

        $filename = 'relatorio-pontos-' . $employee->user->name . '-' . $fromDate->format('Y-m-d') . '-' . $toDate->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}


