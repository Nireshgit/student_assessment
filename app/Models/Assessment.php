<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClassSection;
use Carbon\Carbon;

class Assessment extends Model
{
    /** @use HasFactory<\Database\Factories\AssessmentFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 'start_date', 'end_date', 'class_id', 'grace_time', 'duration', 'seats', 'is_published'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class, 'class_id');
    }

    public function slots()
    {
        return $this->hasMany(AssessmentSlot::class, 'assessment_id');
    }

    public function studentAssessments()
    {
        return $this->hasMany(StudentAssessment::class, 'assessment_id');
    }

    // public function getStartDateAttribute($value)
    // {
    //     return Carbon::parse($value)->format('d-m-Y');
    // }

    // public function getEndDateAttribute($value)
    // {
    //     return Carbon::parse($value)->format('d-m-Y');
    // }
}
