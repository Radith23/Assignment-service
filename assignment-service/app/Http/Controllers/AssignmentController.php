<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    const url = "http://localhost:6000/api/classes";

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $responseClient = Http::get(self::url)['data']['classes'];
        $assignments = Assignment::all()->load('assignmentPlan');

        $result = [];

        foreach ($assignments as $assignment) {
            $classesId = $assignment->course_class_id;
            
            $result[] = [
                'id' => $assignment->id,
                'assignment_plan_id' => $assignment->assignmentPlan->id,
                'course_class_id' => $assignment->course_class_id,
                'assigned_date' => $assignment->assigned_date,
                'due_date' => $assignment->due_date,
                'note' => $assignment->note,
                'course_class' => $responseClient[$classesId-1],
            ];
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'all assignments grabbed',
            'data' => $result,
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Assignment $assignment)
    {
        $responseClient = Http::get(self::url)['data']['classes'];

        $validator = Validator::make($request->all(), [
            'assignment_plan_id' => 'required|numeric',
            'course_class_id' => 'required|numeric',
            'due_date' => 'nullable|date',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $reqClasses = $assignment->course_class_id = $validated['course_class_id'];

        $isExists = false;
        foreach ($responseClient as $resp) {
            if ($reqClasses == $resp['id']) {
                $isExists = true;
            }
        }

        if (!$isExists) {
            return response()->json([
                'status' => 'Failed',
                'message' => "course class id $reqClasses tidak ada dalam database api",
                'data' => "",
            ], 400);
        }
        
        $assignment->assignment_plan_id = $validated['assignment_plan_id'];
        $assignment->assigned_date = Carbon::now('Asia/Jakarta');
        $assignment->due_date = $validated['due_date'];
        $assignment->note = $validated['note'];

        $assignment->save();

        return response()->json([
            'status' => 'Success',
            'message' => 'create assignment success',
            'data' => $assignment,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $assignment = Assignment::where('id', $id)->first();
        
        $responseClient = Http::get(self::url)['data']['classes'];
        
        $classesId = $assignment->course_class_id;
            
        return response()->json([
            'status' => 'Success',
            'message' => 'a department successfully grabbed',
            'data' => [
                'id' => $assignment->id,
                'assignment_plan_id' => $assignment->assignment_plan_id,
                'course_class_id' => $assignment->course_class_id,
                'assigned_date' => $assignment->assigned_date,
                'due_date' => $assignment->due_date,
                'note' => $assignment->note,
                'course_class' => $responseClient[$classesId-1],
                'assignment_plan' => $assignment->assignmentPlan
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $responseClient = Http::get(self::url)['data']['classes'];

        $validated = $request->validate([
            'assignment_plan_id' => 'required|numeric',
            'course_class_id' => 'required|numeric',
            'due_date' => 'nullable|date',
            'note' => 'nullable|string',
        ]);

        $reqClasses = $validated['course_class_id'];

        $isExists = false;
        foreach ($responseClient as $resp) {
            if ($reqClasses == $resp['id']) {
                $isExists = true;
            }
        }

        if (!$isExists) {
            return response()->json([
                'status' => 'Failed',
                'message' => "course class id $reqClasses tidak ada dalam database api",
                'data' => "",
            ], 400);
        }

        $newAssignment = Assignment::where('id', $id)->update($validated);

        return response()->json([
            'status' => 'Success',
            'message' => 'a assignment successfully update',
            'data' => $newAssignment,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        Assignment::destroy($id);

        return response()->json([
            'status' => 'Success',
            'message' => 'a assignment successfully delete',
            'data' => "",
        ], 200);
    }
}
