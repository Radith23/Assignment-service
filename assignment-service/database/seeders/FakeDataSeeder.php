<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assignment;
use App\Models\AssignmentPlan;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assignmentPlans = AssignmentPlan::factory(10)->create();

        foreach ($assignmentPlans as $assignmentPlan) {
            Assignment::factory()->create([
                'assignment_plan_id' => $assignmentPlan->id,
            ]);
        }
    }
}
