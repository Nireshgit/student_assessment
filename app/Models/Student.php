<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'class_id'
    ];

    public function slots()
    {
        return $this->belongsToMany(AssessmentSlot::class, 'student_assessments')
                    ->withPivot('is_attended', 'grace_time');
    }

    public function studentAssignments()
    {
        return $this->hasMany(StudentAssessment::class);
    }

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class, 'class_id');
    }
}
