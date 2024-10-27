<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Assessment;

class ClassSection extends Model
{
    /** @use HasFactory<\Database\Factories\ClassSectionFactory> */
    use HasFactory;

    protected $fillable = [
        'standard', 'section'
    ];

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'class_id');
    }

    public function slots()
    {
        return $this->hasMany(AssessmentSlot::class, 'class_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class,'class_id');
    }
}
