<?php

namespace Database\Factories;

use App\Models\AssignmentPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssignmentPlan>
 */
class AssignmentPlanFactory extends Factory
{
    protected $model = AssignmentPlan::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'objective' => $this->faker->text,
            'title' => $this->faker->text(20),
            'is_group_assignment' => $this->faker->boolean,
            'assignment_style' => $this->faker->words(3, true),
            'description' => $this->faker->text,
            'output_instruction' => $this->faker->text,
            'submission_instruction' => $this->faker->text,
            'deadline_instruction' => $this->faker->text,
        ];
    }
}
