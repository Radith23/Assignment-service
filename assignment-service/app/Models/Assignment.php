<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'assignment_plan_id',
        'course_class_id',
        'assigned_date',
        'due_date',
        'note'
    ];

    public function assignmentPlan()
    {
        return $this->belongsTo(AssignmentPlan::class);
    }
}
