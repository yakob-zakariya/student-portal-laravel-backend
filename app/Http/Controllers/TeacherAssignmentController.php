<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Section;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\TeacherAssignment;

class TeacherAssignmentController extends Controller
{
    public function index(Batch $batch, Semester $semester, Section $section)
    {
        // Step 1: Get all allocated courses for the batch and semester
        $allocations = \App\Models\BatchCourseSemester::with(['course'])
            ->where('batch_id', $batch->id)
            ->where('semester_id', $semester->id)
            ->get();

        // Step 2: For each allocation, check if a teacher is assigned to that section
        $result = $allocations->map(function ($allocation) use ($section) {
            $assignment = TeacherAssignment::with('teacher.user')
                ->where('batch_course_semester_id', $allocation->id)
                ->where('section_id', $section->id)
                ->first();

            return [



                'id' => $allocation->id,
                'batch_id' => $allocation->batch->id,
                'semester_id' => $allocation->semester->id,
                'course' => [
                    'id' => $allocation->course->id,
                    'name' => $allocation->course->name,
                    'code' => $allocation->course->code,
                    'credit_hours' => $allocation->course->credit_hour,
                ],

                'assignment' => $assignment ? [
                    'id' => $assignment?->id,
                    'teacher' => [
                        'id' => $assignment->teacher->id,
                        'user' => [
                            'id' => $assignment->teacher->user->id,
                            'first_name' => $assignment->teacher->user->first_name,
                            'middle_name' => $assignment->teacher->user->middle_name,
                            'last_name' => $assignment->teacher->user->last_name,
                            'email' => $assignment->teacher->user->email,
                            'username' => $assignment->teacher->user->username,

                        ],

                    ],
                    'section' => [
                        'id' => $section->id,
                        'name' => $section->name,
                    ]
                ] : null,

            ];
        });

        return response()->json($result);
    }

    public function store(Request $request, Batch $batch, Semester $semester, Section $section)
    {
        $data = $request->validate([
            'batch_course_semester_id' => 'required|exists:batch_course_semester,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        // Optional: validate the course-semester-batch combo
        $assignment = TeacherAssignment::updateOrCreate(
            [
                'batch_course_semester_id' => $data['batch_course_semester_id'],
                'section_id' => $section->id,
            ],
            [
                'teacher_id' => $data['teacher_id'],
            ]
        );

        return response()->json($assignment->load(['teacher', 'batchCourseSemester.course']));
    }

    public function update(Request $request, Batch $batch, Semester $semester, Section $section, TeacherAssignment $teacherAssignment)
    {

        $data = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
        ]);
        $teacherAssignment->teacher_id = $data['teacher_id'];
        $teacherAssignment->save();

        return response()->json($teacherAssignment->load(['teacher', 'batchCourseSemester.course']));
    }
}
