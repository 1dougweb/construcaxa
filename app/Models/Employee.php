<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'position',
        'department',
        'hire_date',
        'birth_date',
        'cpf',
        'rg',
        'document_id',
        'phone',
        'address',
        'profile_photo_path',
        'document_file',
        'emergency_contact',
        'notes',
        'hourly_rate',
        'monthly_salary',
        'expected_daily_hours',
        'photos',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'birth_date' => 'date',
        'photos' => 'array',
        'hourly_rate' => 'decimal:2',
        'monthly_salary' => 'decimal:2',
        'expected_daily_hours' => 'decimal:2',
    ];

    protected $appends = ['name'];
    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materialRequests()
    {
        return $this->hasMany(MaterialRequest::class, 'user_id', 'user_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'user_id', 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id', 'user_id');
    }

    public function deductions()
    {
        return $this->hasMany(EmployeeDeduction::class);
    }

    public function getNameAttribute()
    {
        return $this->user->name ?? '';
    }

    /**
     * Calcular horas trabalhadas em um período
     */
    public function calculateWorkedHours(\DateTimeInterface $from, \DateTimeInterface $to): float
    {
        $minutes = Attendance::sumWorkedMinutes($this->user_id, $from, $to);
        return round($minutes / 60, 2);
    }

    /**
     * Calcular valor a pagar baseado em horas trabalhadas
     */
    public function calculatePaymentAmount(float $hoursWorked, \DateTimeInterface $from, \DateTimeInterface $to): float
    {
        if ($this->hourly_rate) {
            return round($hoursWorked * $this->hourly_rate, 2);
        }

        if ($this->monthly_salary && $this->expected_daily_hours) {
            // Calcular dias úteis no período
            $workingDays = $this->countWorkingDays($from, $to);
            if ($workingDays > 0) {
                $dailyRate = $this->monthly_salary / 30; // Assumindo 30 dias no mês
                $expectedHours = $workingDays * $this->expected_daily_hours;
                if ($expectedHours > 0) {
                    $hourlyRate = $dailyRate / $this->expected_daily_hours;
                    return round($hoursWorked * $hourlyRate, 2);
                }
            }
        }

        return 0;
    }

    /**
     * Contar dias úteis (segunda a sexta) em um período
     */
    private function countWorkingDays(\DateTimeInterface $from, \DateTimeInterface $to): int
    {
        $count = 0;
        $current = \Carbon\Carbon::parse($from);
        $end = \Carbon\Carbon::parse($to);

        while ($current <= $end) {
            if ($current->isWeekday()) {
                $count++;
            }
            $current->addDay();
        }

        return $count;
    }

    /**
     * Calcular total de descontos em um período
     */
    public function calculateDeductions(\DateTimeInterface $from, \DateTimeInterface $to): float
    {
        return $this->deductions()
            ->whereBetween('date', [$from, $to])
            ->sum('amount');
    }

    public function scopeOrderByName($query)
    {
        return $query->join('users', 'employees.user_id', '=', 'users.id')
                    ->orderBy('users.name')
                    ->select('employees.*');
    }
}
