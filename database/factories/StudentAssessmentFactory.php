<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentAssessment>
 */
class StudentAssessmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assessment_id' => Assessment::factory(),
            'class_id' => ClassSection::factory(),
            'student_id' => Student::factory(),
            'assessment_slot_id' => AssessmentSlot::factory(),
            'is_attended' => $this->faker->boolean,
            'grace_time' => $this->faker->optional()->numberBetween(1, 15),
        ];
    }
}
