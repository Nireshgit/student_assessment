<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssessmentSlot>
 */
class AssessmentSlotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Slot ' . $this->faker->time('H:i'),
            'assessment_id' => Assessment::factory(),
            'class_id' => ClassSection::factory(),
        ];
    }
}
