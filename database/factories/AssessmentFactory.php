<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\ClassSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assessment>
 */
class AssessmentFactory extends Factory
{
    protected $model = Assessment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'class_id' => ClassSection::factory(),
            'grace_time' => $this->faker->numberBetween(5, 15),
            'duration' => $this->faker->numberBetween(1, 3),
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
            'seats' => 20,
            'is_published' => $this->faker->boolean,
        ];
    }
}
