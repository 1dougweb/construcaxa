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
        'cnpj',
        'phone',
        'cellphone',
        'address',
        'profile_photo_path',
        'emergency_contact',
        'notes',
        'photos',
        'cnpj',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'birth_date' => 'date',
        'photos' => 'array',
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

    public function proposals()
    {
        return $this->hasMany(EmployeeProposal::class);
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
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
     * Nota: Este método foi mantido para compatibilidade, mas agora o cálculo
     * deve ser feito através das propostas aceitas (EmployeeProposal)
     */
    public function calculatePaymentAmount(float $hoursWorked, \DateTimeInterface $from, \DateTimeInterface $to): float
    {
        // O cálculo agora deve ser feito através das propostas aceitas
        // Este método retorna 0 para manter compatibilidade
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
