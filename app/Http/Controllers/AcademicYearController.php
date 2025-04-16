<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AcademicYearResource;
use App\Models\AcademicYear;

/**
 * @OA\Tag(
 * name="Academic Year",
 * description="API endpoints for managing academic years"
 * )
 */
class AcademicYearController extends Controller
{
    /**
     * Get all academic years
     *
     * @OA\Get(
     * path="/api/v1/academic-years",
     * summary="List all academic years",
     * tags={"Academic Year"},
     * @OA\Response(
     * response=200,
     * description="List of academic years",
     * @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/AcademicYear"))
     * )
     * )
     */
    public function index()
    {
        $academicYears = AcademicYear::with('semesters')->get();
        return AcademicYearResource::collection($academicYears);
    }

    /**
     * Store a new academic year
     *
     * @OA\Post(
     * path="/api/academic-years",
     * summary="Create a new academic year",
     * tags={"Academic Year"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name"},
     * @OA\Property(property="name", type="string", example="2024/2025")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Academic year created",
     * @OA\JsonContent(ref="#/components/schemas/AcademicYear")
     * )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:academic_years,name'],
        ]);

        $academicYear = AcademicYear::create($validated);
        return new AcademicYearResource($academicYear);
    }

    /**
     * Get details of a specific academic year
     *
     * @OA\Get(
     * path="/api/academic-years/{id}",
     * summary="Get academic year by ID",
     * tags={"Academic Year"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the academic year",
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Academic year details",
     * @OA\JsonContent(ref="#/components/schemas/AcademicYear")
     * )
     * )
     */
    public function show(AcademicYear $academicYear)
    {
        $academicYear->load('semesters');
        return new AcademicYearResource($academicYear);
    }

    /**
     * Update an existing academic year
     *
     * @OA\Put(
     * path="/api/academic-years/{id}",
     * summary="Update an academic year",
     * tags={"Academic Year"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the academic year to update",
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="name", type="string", example="2025/2026")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Academic year updated",
     * @OA\JsonContent(ref="#/components/schemas/AcademicYear")
     * )
     * )
     */
    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255', 'unique:academic_years,name,' . $academicYear->id],
        ]);
        $academicYear->update($validated);
        $academicYear->load('semesters');

        return new AcademicYearResource($academicYear);
    }

    /**
     * Delete an academic year
     *
     * @OA\Delete(
     * path="/api/academic-years/{id}",
     * summary="Delete an academic year",
     * tags={"Academic Year"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the academic year to delete",
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=204,
     * description="Academic year deleted"
     * )
     * )
     */
    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();
        return response()->json([
            "message" => "Academic Year Deleted Successfully"
        ], 204);
    }
}
