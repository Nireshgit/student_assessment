<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\ClassSection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        $existingClassSection = ClassSection::inRandomOrder()->first();

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->numerify('##########'), // Generates a 10-digit phone number
            'class_id' => $existingClassSection ? $existingClassSection->id : null, //ClassSection::factory(), // This will create a new ClassSection or use an existing one if available
        ];
    }
}
