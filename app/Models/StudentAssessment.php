<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssessment extends Model
{
    /** @use HasFactory<\Database\Factories\StudentAssessmentFactory> */
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'student_id',
        'class_id',
        'assessment_slot_id',
        'is_attended',
        'assessment_date'
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function slot()
    {
        return $this->belongsTo(AssessmentSlot::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
