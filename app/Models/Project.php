<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'slug',
        'client_id',
        'address',
        'latitude',
        'longitude',
        'start_date',
        'end_date_estimated',
        'status',
        'progress_percentage',
        'notes',
        'os_number',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = static::generateUniqueSlug($project->name, $project->code);
            }
        });

        static::updating(function ($project) {
            if ($project->isDirty('name') || $project->isDirty('code')) {
                $project->slug = static::generateUniqueSlug($project->name, $project->code, $project->id);
            }
        });
    }

    protected static function generateUniqueSlug($name, $code, $exceptId = null)
    {
        $baseSlug = Str::slug($name . '-' . $code);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->when($exceptId, function ($query) use ($exceptId) {
            return $query->where('id', '!=', $exceptId);
        })->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $casts = [
        'start_date' => 'date',
        'end_date_estimated' => 'date',
        'progress_percentage' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'project_employee')
            ->withPivot(['role_on_project'])
            ->withTimestamps();
    }

    public function updates(): HasMany
    {
        return $this->hasMany(ProjectUpdate::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ProjectPhoto::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function materialRequests(): HasMany
    {
        return $this->hasMany(MaterialRequest::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class);
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(ProjectBudget::class);
    }

    public function accountPayables(): HasMany
    {
        return $this->hasMany(AccountPayable::class);
    }

    public function accountReceivables(): HasMany
    {
        return $this->hasMany(AccountReceivable::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    public function financialBalances(): HasMany
    {
        return $this->hasMany(ProjectFinancialBalance::class);
    }

    /**
     * Generate a unique OS number for this project
     */
    public function generateOsNumber(): string
    {
        $year = date('Y');
        $prefix = "OS{$year}";
        
        // Find the highest existing OS number for this year
        $lastOs = static::where('os_number', 'like', "{$prefix}%")
            ->orderByRaw('CAST(SUBSTRING(os_number, ' . (strlen($prefix) + 1) . ') AS UNSIGNED) DESC')
            ->first();
        
        if ($lastOs && preg_match('/OS\d{4}(\d+)/', $lastOs->os_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Assign OS number when budget is approved
     */
    public function assignOsNumber(): void
    {
        if (!$this->os_number) {
            $this->os_number = $this->generateOsNumber();
            $this->save();
        }
    }

    /**
     * Create a project from an approved budget
     */
    public static function createFromApprovedBudget(ProjectBudget $budget): Project
    {
        $client = $budget->client;
        
        // Generate project code from budget
        $code = 'PROJ-' . date('Y') . '-' . str_pad($budget->id, 4, '0', STR_PAD_LEFT);
        
        // Ensure unique code
        $counter = 1;
        $originalCode = $code;
        while (static::where('code', $code)->exists()) {
            $code = $originalCode . '-' . $counter;
            $counter++;
        }
        
        $project = static::create([
            'name' => 'Obra - ' . ($client ? $client->name : 'Cliente #' . $budget->client_id),
            'code' => $code,
            'client_id' => $budget->client_id,
            'address' => $budget->address,
            'status' => 'planned',
            'progress_percentage' => 0,
        ]);
        
        // Generate OS number
        $project->assignOsNumber();
        
        // Link budget to project
        $budget->project_id = $project->id;
        $budget->save();
        
        return $project;
    }
}


