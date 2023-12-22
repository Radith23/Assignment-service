<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assignment_plans', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('syllabus_id')->constrained('syllabi');
            $table->text('objective')->nullable();
            $table->string('title', 2048)->nullable();
            $table->boolean('is_group_assignment')->nullable();
            $table->string('assignment_style', 1024)->nullable();
            $table->text('description')->nullable();
            $table->text('output_instruction')->nullable();
            $table->text('submission_instruction')->nullable();
            $table->text('deadline_instruction')->nullable();
            $table->timestampsTz();
        });

        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_plan_id')->constrained('assignment_plans');
            // $table->foreignId('course_class_id')->constrained('course_classes');
            $table->bigInteger('course_class_id');
            $table->timestamp('assigned_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->text('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_plans');
        Schema::dropIfExists('assignments');
    }
};