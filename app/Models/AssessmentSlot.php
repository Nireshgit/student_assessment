<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AssessmentSlot extends Model
{
    /** @use HasFactory<\Database\Factories\AssessmentSlotFactory> */
    use HasFactory;

    protected $casts = [
        'assessment_date' => 'datetime',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class, 'class_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_assessments')
                    ->withPivot('is_attended', 'grace_time', 'assessment_date');
    }

    // public function getAssessmentDateAttribute($value)
    // {
    //     return Carbon::parse($value)->format('d-m-Y');
    // }
}
