<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AcademicYearResource;
use App\Models\AcademicYear;

class AcademicYearController extends Controller
{
    public function index()
    {

        $academicYears = AcademicYear::with('semesters')->get();

        return AcademicYearResource::collection($academicYears);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:academic_years,name'],
        ]);

        $academicYear = AcademicYear::create($validated);

        return new AcademicYearResource($academicYear);
    }

    public function show(AcademicYear $academicYear)
    {
        $academicYear->load('semesters');
        return new AcademicYearResource($academicYear);
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255', 'unique:academic_years,name,' . $academicYear->id],
        ]);
        $academicYear->update($validated);
        $academicYear->load('semesters');

        return new AcademicYearResource($academicYear);
    }
}
