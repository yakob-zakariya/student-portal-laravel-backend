<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Semester;
use App\Models\Course;
use Illuminate\Validation\Rule;
use App\Models\BatchCourseSemester;
use App\Http\Resources\AllocatedCoursesResource;
use Illuminate\Support\Facades\DB;

class CourseAllocationController extends Controller
{


    public function index(Batch $batch, Semester $semester)
    {
        $batch->load('department');
        $semester->load('academicYear');


        $courses = $batch->courses()->wherePivot('semester_id', $semester->id)->get();
        return new AllocatedCoursesResource([
            'batch' => $batch,
            'semester' => $semester,
            'courses' => $courses,
        ]);
    }



    public function available_courses(Batch $batch)
    {

        // Get all course IDs already allocated to the batch, irrespective of the semester
        $allocatedCourses = $batch->courses()->pluck('courses.id')->toArray(); // Specify 'courses.id'

        // Now, get all courses that are not in the allocated list
        $availableCourses = Course::whereNotIn('id', $allocatedCourses)->get();

        return CourseResource::collection($availableCourses);
    }


    public function allocate_courses(Request $request, Batch $batch, Semester $semester)
    {
        $validated = $request->validate([
            'courses' => ['required', 'array'],
            'courses.*' => [
                'required',
                'integer',
                'exists:courses,id',
                Rule::exists('courses', 'id'), // Ensure course exists
            ]
        ]);

        $courseIds = $validated['courses'];

        // Check if any of the courses are already allocated to the batch for the given semester
        $existingCourses = BatchCourseSemester::where('batch_id', $batch->id)
            ->where('semester_id', $semester->id)
            ->whereIn('course_id', $courseIds)
            ->pluck('course_id')
            ->toArray();

        if ($existingCourses) {
            return response()->json([
                'message' => 'Some courses are already allocated to this batch in the selected semester.',
                'courses' => $existingCourses
            ], 400);
        }

        // Use a transaction to ensure that all courses are allocated atomically
        DB::beginTransaction();
        try {
            foreach ($courseIds as $courseId) {
                BatchCourseSemester::create([
                    'course_id' => $courseId,
                    'semester_id' => $semester->id,
                    'batch_id' => $batch->id,
                ]);
            }
            DB::commit();
            return response()->json($batch->courses()->wherePivot('semester_id', $semester->id)->get(), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error allocating courses'], 500);
        }
    }


    public function deallocate_courses(Request $request, Batch $batch, Semester $semester)
    {
        $validated = $request->validate([
            'courses' => ['required', 'array'],
            'courses.*' => [
                'required',
                'integer',
                Rule::exists('courses', 'id'),
                function ($attribute, $value, $fail) use ($batch, $semester) {
                    $exists = BatchCourseSemester::where('batch_id', $batch->id)
                        ->where('course_id', $value)
                        ->where('semester_id', $semester->id)
                        ->exists();

                    if (!$exists) {
                        $fail("The course with ID {$value} is not allocated to this batch in this semester.");
                    }
                },
            ]
        ]);

        // Deallocate courses
        DB::beginTransaction();
        try {
            foreach ($validated['courses'] as $course_id) {
                BatchCourseSemester::where(['batch_id' => $batch->id, 'course_id' => $course_id, 'semester_id' => $semester->id])->delete();
            }
            DB::commit();
            return response()->json($batch->courses()->wherePivot('semester_id', $semester->id)->get(), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error deallocating courses'], 500);
        }
    }



    public function sync_courses(Request $request, Batch $batch, Semester $semester)
    {
        $validated = $request->validate([
            'courses' => ['required', 'array'],
            'courses.*' => [
                'required',
                'integer',
                Rule::exists('courses', 'id'),
            ],
        ]);

        $newCourseIds = collect($validated['courses'])->unique()->values();

        // Get current allocated course IDs for this batch & semester
        $currentCourseIds = BatchCourseSemester::where('batch_id', $batch->id)
            ->where('semester_id', $semester->id)
            ->pluck('course_id');

        // Courses to add and remove
        $toAdd = $newCourseIds->diff($currentCourseIds);
        $toRemove = $currentCourseIds->diff($newCourseIds);

        // Add new courses
        DB::beginTransaction();
        try {
            foreach ($toAdd as $courseId) {
                BatchCourseSemester::create([
                    'course_id' => $courseId,
                    'batch_id' => $batch->id,
                    'semester_id' => $semester->id,
                ]);
            }

            // Remove courses
            BatchCourseSemester::where('batch_id', $batch->id)
                ->where('semester_id', $semester->id)
                ->whereIn('course_id', $toRemove)
                ->delete();

            DB::commit();
            $updatedCourses = $batch->courses()->wherePivot('semester_id', $semester->id)->get();
            return response()->json([
                'message' => 'Courses synchronized successfully.',
                'added' => $toAdd,
                'removed' => $toRemove,
                'updated_courses' => $updatedCourses
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error synchronizing courses'], 500);
        }
    }
}
