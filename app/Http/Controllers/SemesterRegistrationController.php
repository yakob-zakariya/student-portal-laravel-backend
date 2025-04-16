<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\BatchCourseSemester;
use App\Models\CourseRegistration;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\SemesterRegistration;
use App\Models\Semester;

class SemesterRegistrationController extends Controller
{

    public function registration_status(Semester $semester)
    {
        sleep(3);
        $user = Auth::user();
        $student = $user->student;
        $batch = $student->batch;

        // 1. Check if the student already registered
        $existingRegistration = CourseRegistration::with('courses')
            ->where('student_id', $student->id)
            ->where('semester_id', $semester->id)
            ->first();

        // $existingRegistration = true;
        if ($existingRegistration) {
            return response()->json([
                'status' => 'already_registered',
                'courses' => $existingRegistration->courses,
                // 'courses' => $batch->courses()->wherePivot('semester_id', $semester->id)->get(),
            ]);
        }

        // 2. registration is open for the selected semester
        $open = $semester->registration_open;
        if (!$open) {
            return response()->json([
                'status' => 'registration_closed',
                'message' => 'Registration is not open for the selected semester.',
            ]);
        }



        // 3. Get available allocated courses for this student's batch
        $allocatedCourses = $batch->courses()
            ->wherePivot('semester_id', $semester->id)
            ->get();

        return response()->json([
            'status' => 'registration_open',
            'courses' => $allocatedCourses,
        ]);
    }

    public function allocated_courses(Semester $semester)
    {
        $batch = Auth::user()->student->batch;
        return $batch->courses()->wherePivot('semester_id', $semester->id)->get();
    }



    public function registerCourses(Request $request)
    {

        $user = Auth::user();
        $batch = $user->student->batch;


        $validated = $request->validate([
            'semester_id' => ['required', 'integer', 'exists:semesters,id'],
            'courses' => ['required', 'array'],
            'courses.*' => [
                'required',
                'integer',
                'exists:courses,id',
                function ($attribute, $value, $fail) use ($batch, $request) {
                    $exists = BatchCourseSemester::where('batch_id', $batch->id)
                        ->where('course_id', $value)
                        ->where('semester_id', $request->semester_id)
                        ->exists();

                    if (!$exists) {
                        $fail("The course with ID {$value} is not allocated to this batch.");
                    }
                },
            ],
        ]);


        DB::transaction(function () use ($validated, $user) {

            $total_credit_hour = 0;

            foreach ($validated['courses'] as $courseId) {
                $course = Course::find($courseId);
                $total_credit_hour += $course->credit_hour;


                CourseRegistration::firstOrCreate([
                    'course_id' => $courseId,
                    'student_id' => $user->student->id,
                    'semester_id' => $validated['semester_id'],
                    'section_id' => ($user->student->section) ? $user->student->section->id : null,
                ]);
            }

            // dd('nothing');

            SemesterRegistration::firstOrCreate([
                'total_credit_hour' => $total_credit_hour,
                'student_id' => $user->student->id,
                'semester_id' => $validated['semester_id']

            ]);
            // dd('nothing');
        });


        $courses = $user->student->courses()->wherePivot('semester_id', $validated['semester_id'])->get();
        // dd($courses);


        return response()->json([
            'message' => 'You have registered to the following Courses successfully',
            'courses' => $courses
        ]);
    }
}
