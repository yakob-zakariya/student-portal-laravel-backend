<?php

use Illuminate\Support\Facades\Route;
use App\Enums\Permission as PermissionEnum;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;

use App\Http\Controllers\User\RegistrarUserController;

use App\Http\Controllers\CourseController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\User\CoordinatorUserController;
use App\Http\Controllers\DepartmentController;





// Public Routes
Route::group(['middleware' => ['guest']], function () {
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


// Role And Permission Routes
Route::group(['middleware' => ['auth:sanctum']], function () {


    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:' . PermissionEnum::VIEW_ROLE->value);

    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:' . PermissionEnum::CREATE_ROLE->value);

    Route::get('/roles/{role}', [RoleController::class, 'show'])->middleware('permission:' . PermissionEnum::VIEW_ROLE->value);

    Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('permission:' . PermissionEnum::UPDATE_ROLE->value);

    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('permission:' . PermissionEnum::DELETE_ROLE->value);


    Route::get('/permissions', [RolePermissionController::class, 'permissions'])->middleware('permission:' . PermissionEnum::VIEW_PERMISSION->value);




    Route::post('/roles/{role}/assign-permissions', [RolePermissionController::class, 'assignPermissionsToRole'])->middleware('permission:' . PermissionEnum::ASSIGN_PERMISSION->value);

    Route::post('/roles/{role}/revoke-permissions', [RolePermissionController::class, 'revokePermissionFromRole'])->middleware('permission:' . PermissionEnum::REVOVE_PERMISSION->value);
});


// Department Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/departments', [DepartmentController::class, 'index'])->middleware('permission:' . PermissionEnum::VIEW_DEPARTMENT->value);

    Route::post('/departments', [DepartmentController::class, 'store'])->middleware('permission:' . PermissionEnum::CREATE_DEPARTMENT->value);

    Route::get('/departments/{department}', [DepartmentController::class, 'show'])->middleware('permission:' . PermissionEnum::VIEW_DEPARTMENT->value);

    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->middleware('permission:' . PermissionEnum::UPDATE_DEPARTMENT->value);

    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->middleware('permission:' . PermissionEnum::DELETE_DEPARTMENT->value);
});

// User Creation Routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    // Registrar User Management Routes
    Route::get('/registrars', [RegistrarUserController::class, 'index'])->middleware('permission:' . PermissionEnum::VIEW_REGISTRAR_USER->value);

    Route::post('/registrars', [RegistrarUserController::class, 'store'])->middleware('permission:' . PermissionEnum::CREATE_REGISTRAR_USER->value);

    Route::get('/registrars/{user}', [RegistrarUserController::class, 'show'])->middleware('permission:' . PermissionEnum::VIEW_REGISTRAR_USER->value);

    Route::put('/registrars/{user}', [RegistrarUserController::class, 'update'])->middleware('permission:' . PermissionEnum::UPDATE_REGISTRAR_USER->value);


    Route::delete('/registrars/{user}', [RegistrarUserController::class, 'destory'])->middleware('permission:' . PermissionEnum::DELETE_REGISTRAR_USER->value);


    // Coordinator User Management Routes

    Route::get('/coordinators', [CoordinatorUserController::class, 'index'])->middleware('permission:' . PermissionEnum::VIEW_COORDINATOR_USER->value);

    Route::post('/coordinators', [CoordinatorUserController::class, 'store'])->middleware('permission:' . PermissionEnum::CREATE_COORDINATOR_USER->value);

    Route::get('/coordinators/{user}', [CoordinatorUserController::class, 'show'])->middleware('permission:' . PermissionEnum::VIEW_COORDINATOR_USER->value);

    Route::put('/coordinators/{user}', [CoordinatorUserController::class, 'update'])->middleware('permission:' . PermissionEnum::UPDATE_COORDINATOR_USER->value);

    Route::delete('/coordinators/{user}', [CoordinatorUserController::class, 'destroy'])->middleware('permission:' . PermissionEnum::DELETE_COORDINATOR_USER->value);

    // Teacher User Management Routes


    // Student User Management Routes
});

// Course Routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/courses', [CourseController::class, 'index'])->middleware('permission:' . PermissionEnum::VIEW_COURSE->value);

    Route::post('/courses', [CourseController::class, 'store'])->middleware('permission:' . PermissionEnum::CREATE_COURSE->value);

    Route::get('/courses/{course}', [CourseController::class, 'show'])->middleware('permission:' . PermissionEnum::VIEW_COURSE->value);

    Route::put('/courses/{course}', [CourseController::class, 'update'])->middleware('permission:' . PermissionEnum::UPDATE_COURSE->value);

    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->middleware('permission:' . PermissionEnum::DELETE_COURSE->value);
});

// Academic Year Routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/academic-years', [AcademicYearController::class, 'index'])->middleware('permission:' . PermissionEnum::VIEW_ACADEMIC_YEAR->value);

    Route::post('/academic-years', [AcademicYearController::class, 'store'])->middleware('permission:' . PermissionEnum::CREATE_ACADEMIC_YEAR->value);

    Route::get('/academic-years/{academicYear}', [AcademicYearController::class, 'show'])->middleware('permission:' . PermissionEnum::VIEW_ACADEMIC_YEAR->value);

    Route::put('/academic-years/{academicYear}', [AcademicYearController::class, 'update'])->middleware('permission:
    ' . PermissionEnum::UPDATE_ACADEMIC_YEAR->value);

    Route::delete('/academic-years/{academicYear}', [AcademicYearController::class, 'destroy'])->middleware('permission:' . PermissionEnum::DELETE_ACADEMIC_YEAR->value);
});
