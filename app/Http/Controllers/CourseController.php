<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Http\Resources\CourseResource;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::paginate(5);

        return CourseResource::collection($courses);
    }

    public function store(Request $request)
    {


        $validated =  $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'unique:courses,code'],
            'credit_hour' => ['required', 'integer'],
        ]);

        $course = Course::create($validated);

        return new CourseResource($course, 201);
    }

    public function show(Course $course)
    {
        return new CourseResource($course);
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'code' => ['sometimes', 'string', 'unique:courses,code,' . $course->id],
            'credit_hour' => ['sometimes', 'integer'],
        ]);
        $course->update($validated);

        return new CourseResource($course);
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return response()->json([
            'message' => 'Course Deleted Successfully'
        ]);
    }
}
