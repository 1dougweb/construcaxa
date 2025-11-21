<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaborType extends Model
{
    use HasFactory, SoftDeletes;

    // Skill level constants
    const SKILL_JUNIOR = 'junior';
    const SKILL_SENIOR = 'senior';
    const SKILL_SPECIALIST = 'specialist';

    protected $fillable = [
        'name',
        'description',
        'skill_level',
        'hourly_rate',
        'overtime_rate',
        'is_active',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get all available skill levels
     */
    public static function getSkillLevels(): array
    {
        return [
            self::SKILL_JUNIOR => 'Júnior',
            self::SKILL_SENIOR => 'Sênior',
            self::SKILL_SPECIALIST => 'Especialista',
        ];
    }

    /**
     * Get budget items that use this labor type
     */
    public function budgetItems(): HasMany
    {
        return $this->hasMany(ProjectBudgetItem::class, 'labor_type_id');
    }

    /**
     * Scope to get only active labor types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the skill level label
     */
    public function getSkillLevelLabelAttribute(): string
    {
        return self::getSkillLevels()[$this->skill_level] ?? ucfirst($this->skill_level);
    }

    /**
     * Get formatted hourly rate
     */
    public function getFormattedHourlyRateAttribute(): string
    {
        return 'R$ ' . number_format($this->hourly_rate, 2, ',', '.') . '/hora';
    }

    /**
     * Get formatted overtime rate
     */
    public function getFormattedOvertimeRateAttribute(): string
    {
        return 'R$ ' . number_format($this->overtime_rate, 2, ',', '.') . '/hora';
    }

    /**
     * Calculate labor cost based on hours
     */
    public function calculateCost(float $regularHours, float $overtimeHours = 0): float
    {
        $regularCost = $regularHours * $this->hourly_rate;
        $overtimeCost = $overtimeHours * $this->overtime_rate;
        
        return $regularCost + $overtimeCost;
    }

    /**
     * Get skill level color for UI
     */
    public function getSkillLevelColorAttribute(): string
    {
        return match($this->skill_level) {
            self::SKILL_JUNIOR => 'bg-blue-100 text-blue-800',
            self::SKILL_SENIOR => 'bg-green-100 text-green-800',
            self::SKILL_SPECIALIST => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
