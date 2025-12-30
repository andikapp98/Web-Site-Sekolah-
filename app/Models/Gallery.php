<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'image',
        'video_url',
        'thumbnail',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePhotos($query)
    {
        return $query->where('type', 'photo');
    }

    public function scopeReels($query)
    {
        return $query->where('type', 'reels');
    }

    public function scopeYoutube($query)
    {
        return $query->where('type', 'youtube');
    }

    // Extract YouTube video ID from URL
    public function getYoutubeIdAttribute()
    {
        if ($this->type !== 'youtube' || !$this->video_url) {
            return null;
        }

        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->video_url, $matches);
        
        return $matches[1] ?? null;
    }
}
