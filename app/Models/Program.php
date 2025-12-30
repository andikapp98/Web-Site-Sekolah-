<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'short_name',
        'description',
        'full_description',
        'career_opportunities',
        'competencies',
        'facilities',
        'equipment',
        'image',
        'career_image',
        'workshop_image',
        'video',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'competencies' => 'array',
        'facilities' => 'array',
        'equipment' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($program) {
            if (empty($program->slug)) {
                $program->slug = Str::slug($program->name);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    public function getVideoUrlAttribute()
    {
        if ($this->video) {
            return asset('storage/' . $this->video);
        }
        return null;
    }

    public function getCareerImageUrlAttribute()
    {
        if ($this->career_image) {
            return asset('storage/' . $this->career_image);
        }
        return null;
    }

    public function getWorkshopImageUrlAttribute()
    {
        if ($this->workshop_image) {
            return asset('storage/' . $this->workshop_image);
        }
        return null;
    }
}
