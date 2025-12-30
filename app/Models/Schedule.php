<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'class_name',
        'day',
        'start_time',
        'end_time',
        'subject',
        'teacher_id',
        'room',
        'semester',
        'academic_year',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByDay($query, $day)
    {
        return $query->where('day', $day);
    }

    public function scopeByClass($query, $className)
    {
        return $query->where('class_name', $className);
    }

    public function scopeByProgram($query, $programId)
    {
        return $query->where('program_id', $programId);
    }

    public function scopeCurrentAcademicYear($query)
    {
        $currentYear = date('Y');
        $month = date('n');
        
        // Academic year starts in July
        if ($month >= 7) {
            $academicYear = $currentYear . '/' . ($currentYear + 1);
        } else {
            $academicYear = ($currentYear - 1) . '/' . $currentYear;
        }
        
        return $query->where('academic_year', $academicYear);
    }
}
