<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'name',
        'original_name',
        'path',
        'mime_type',
        'size',
        'description',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file icon based on mime type
     */
    public function getFileIconAttribute(): string
    {
        $mimeType = $this->mime_type;
        
        if (str_starts_with($mimeType, 'image/')) {
            return '🖼️';
        }
        
        if (str_starts_with($mimeType, 'application/pdf')) {
            return '📄';
        }
        
        if (str_contains($mimeType, 'word') || str_contains($mimeType, 'document')) {
            return '📝';
        }
        
        if (str_contains($mimeType, 'excel') || str_contains($mimeType, 'spreadsheet')) {
            return '📊';
        }
        
        if (str_contains($mimeType, 'powerpoint') || str_contains($mimeType, 'presentation')) {
            return '📽️';
        }
        
        if (str_contains($mimeType, 'zip') || str_contains($mimeType, 'archive')) {
            return '📦';
        }
        
        if (str_contains($mimeType, 'text')) {
            return '📃';
        }
        
        return '📎';
    }

    /**
     * Get file extension
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->original_name, PATHINFO_EXTENSION);
    }
}

