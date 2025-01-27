<?php

use App\Http\Controllers\CourseController;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\RolePermissionController;

// Auth Routes

Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', [AuthController::class, 'getUser'])->middleware('auth:sanctum');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sancturm');

// Role And Permission Routes
Route::apiResource('roles', RoleController::class)->middleware(['auth:sanctum', 'role:ADMIN']);

Route::get('/permissions', [RolePermissionController::class, 'permissions'])->middleware(['auth:sanctum', 'role:ADMIN']);


Route::post('/roles/{role}/assign-permissions', [RolePermissionController::class, 'assignPermissionsToRole'])->middleware(['auth:sanctum', 'role:ADMIN']);

Route::post('/roles/{role}/revoke-permissions', [RolePermissionController::class, 'revokePermissionFromRole'])->middleware(['auth:sanctum', 'role:ADMIN']);







// Course Routes

// Route::apiResource('courses', CourseController::class)->middleware(['auth:sanctum', 'permission:course-list|course-create|course-edit|course-delete']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/courses', [CourseController::class, 'index'])->middleware('permission:course-list');

    Route::post('/courses', [CourseController::class, 'store'])->middleware('permission:course-create');

    Route::get('/courses/{course}', [CourseController::class, 'show'])->middleware('permission:course-list');

    Route::put('/courses/{course}', [CourseController::class, 'update'])->middleware('permission:course-edit');

    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->middleware('permission:course-delete');
});



// Academic Year Routes
// Route::apiResource('academic-years', AcademicYearController::class)->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/academic-years', [AcademicYearController::class, 'index'])->middleware('permission:academicYear-list');

    Route::post('/academic-years', [AcademicYearController::class, 'store'])->middleware('permission:academicYear-create');

    Route::get('/academic-years/{academicYear}', [AcademicYearController::class, 'show'])->middleware('permission:academicYear-show');

    Route::put('/academic-years/{academicYear}', [AcademicYearController::class, 'update'])->middleware('permission:academicYear-edit');

    Route::delete('/academic-years/{academicYear}', [AcademicYearController::class, 'destroy'])->middleware('permission:academicYear-delete');
});
