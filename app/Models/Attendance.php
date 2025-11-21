<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'latitude',
        'longitude',
        'accuracy',
        'punched_at',
        'punched_date',
        'notes',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'accuracy' => 'decimal:2',
        'punched_at' => 'datetime',
        'punched_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function sumWorkedMinutes(int $userId, \DateTimeInterface $from, \DateTimeInterface $to): int
    {
        $rows = self::where('user_id', $userId)
            ->whereBetween('punched_at', [$from, $to])
            ->orderBy('punched_at')
            ->get(['type', 'punched_at']);

        $minutes = 0;
        $openEntry = null;
        foreach ($rows as $row) {
            if ($row->type === 'entry') {
                $openEntry = $row->punched_at;
            } elseif ($row->type === 'exit' && $openEntry) {
                $minutes += $row->punched_at->diffInMinutes($openEntry);
                $openEntry = null;
            }
        }
        return $minutes;
    }
}


