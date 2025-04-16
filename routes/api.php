<?php

use Illuminate\Support\Facades\Route;
use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;

use App\Http\Controllers\User\TeacherUserController;
use App\Http\Controllers\User\StudentUserController;

use App\Http\Controllers\CourseController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\User\CoordinatorUserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\CourseAllocationController;

use App\Http\Controllers\SemesterRegistrationController;

use App\Http\Controllers\TeacherAssignmentController;




// Public Auth Routes
Route::group(['middleware' => ['guest']], function () {
    Route::post('/login', [AuthController::class, 'login']);
});

// protected Auth Routes
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


// User Management Routes
Route::prefix('/users')->middleware('auth:sanctum')->group(function () {

    // Coordinator User Management Routes
    Route::apiResource('coordinators', CoordinatorUserController::class)->parameters([
        'coordinators' => 'user'
    ])->middleware([
        'index' => 'permission:' . PermissionEnum::VIEW_COORDINATOR_USER->value,
        'store' => 'permission:' . PermissionEnum::CREATE_COORDINATOR_USER->value,
        'show' => 'permission:' . PermissionEnum::VIEW_COORDINATOR_USER->value,
        'update' => 'permission:' . PermissionEnum::UPDATE_COORDINATOR_USER->value,
        'destroy' => 'permission:' . PermissionEnum::DELETE_COORDINATOR_USER->value,
    ]);



    // Teacher User Management Routes
    Route::apiResource('teachers', TeacherUserController::class)->parameters(['teachers' => 'user'])->middleware([
        'index' => 'permission:' . PermissionEnum::VIEW_TEACHER_USER->value,
        'show' => 'permission:' . PermissionEnum::VIEW_TEACHER_USER->value,
        'store' => 'permission:' . PermissionEnum::CREATE_TEACHER_USER->value,
        'update' => 'permission:' . PermissionEnum::UPDATE_TEACHER_USER->value,
        'destroy' => 'permission:' . PermissionEnum::DELETE_TEACHER_USER->value
    ]);



    // Student User Management Routes
    Route::apiResource('students', StudentUserController::class)->parameters(['students' => 'user'])
        ->middleware([
            'index' => 'permission:' . PermissionEnum::VIEW_STUDENT_USER->value,
            'store' => 'permission:' . PermissionEnum::CREATE_STUDENT_USER->value,
            'show' => 'permission:' . PermissionEnum::VIEW_STUDENT_USER->value,
            'update' => 'permission:' . PermissionEnum::UPDATE_STUDENT_USER->value,
            'delete' => 'permission:' . PermissionEnum::DELETE_STUDENT_USER->value,
        ]);
});




// Course Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/courses', [CourseController::class, 'index'])->middleware('permission:' . PermissionEnum::VIEW_COURSE->value);
    Route::post('/courses', [CourseController::class, 'store'])->middleware('permission:' . PermissionEnum::CREATE_COURSE->value);
    Route::get('/courses/{course}', [CourseController::class, 'show'])->middleware('permission:' . PermissionEnum::VIEW_COURSE->value);
    Route::put('/courses/{course}', [CourseController::class, 'update'])->middleware('permission:' . PermissionEnum::UPDATE_COURSE->value);
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->middleware('permission:' . PermissionEnum::DELETE_COURSE->value);
});



// Academic Year And Semester Routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/academic-years', [AcademicYearController::class, 'index'])->middleware('permission:' . PermissionEnum::VIEW_ACADEMIC_YEAR->value);
    Route::post('/academic-years', [AcademicYearController::class, 'store'])->middleware('permission:' . PermissionEnum::CREATE_ACADEMIC_YEAR->value);
    Route::get('/academic-years/{academicYear}', [AcademicYearController::class, 'show'])->middleware('permission:' . PermissionEnum::VIEW_ACADEMIC_YEAR->value);
    Route::put('/academic-years/{academicYear}', [AcademicYearController::class, 'update'])->middleware('permission:' . PermissionEnum::UPDATE_ACADEMIC_YEAR->value);
    Route::delete('/academic-years/{academicYear}', [AcademicYearController::class, 'destroy'])->middleware('permission:' . PermissionEnum::DELETE_ACADEMIC_YEAR->value);


    Route::get('/academic-years/{academicYear}/semesters', [SemesterController::class, 'index'])->middleware('permission:' . PermissionEnum::VIEW_SEMESTER->value);
    Route::post('/academic-years/{academicYear}/semesters', [SemesterController::class, 'store'])->middleware('permission:' . PermissionEnum::CREATE_SEMESTER->value);
    Route::get('/academic-years/{academicYear}/semesters/{semester}', [SemesterController::class, 'show'])->middleware('permission:' . PermissionEnum::VIEW_SEMESTER->value);
    Route::put('/academic-years/{academicYear}/semesters/{semester}', [SemesterController::class, 'update'])->middleware('permission:' . PermissionEnum::UPDATE_SEMESTER->value);
    Route::delete('/academic-years/{academicYear}/semesters/{semester}', [SemesterController::class, 'destroy'])->middleware('permission:' . PermissionEnum::DELETE_SEMESTER->value);
});


// Department , Batch And Section Routes

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/departments', [DepartmentController::class, 'index'])->middleware('permission:' . PermissionEnum::VIEW_DEPARTMENT->value);
    Route::post('/departments', [DepartmentController::class, 'store'])->middleware('permission:' . PermissionEnum::CREATE_DEPARTMENT->value);
    Route::get('/departments/{department}', [DepartmentController::class, 'show'])->middleware('permission:' . PermissionEnum::VIEW_DEPARTMENT->value);
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->middleware('permission:' . PermissionEnum::UPDATE_DEPARTMENT->value);
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->middleware('permission:' . PermissionEnum::DELETE_DEPARTMENT->value);


    Route::get('/departments/{department}/batches', [BatchController::class, 'index'])->middleware('permission:' . PermissionEnum::VIEW_BATCH->value);
    Route::post('/departments/{department}/batches', [BatchController::class, 'store'])->middleware('permission:' . PermissionEnum::CREATE_BATCH->value);
    Route::get('/departments/{department}/batches/{batch}', [BatchController::class, 'show'])->middleware('permission:' . PermissionEnum::VIEW_BATCH->value);
    Route::put('/departments/{department}/batches/{batch}', [BatchController::class, 'update'])->middleware('permission:' . PermissionEnum::UPDATE_BATCH->value);
    Route::delete('/departments/{department}/batches/{batch}', [BatchController::class, 'destroy'])->middleware('permission:' . PermissionEnum::DELETE_BATCH->value);



    Route::get('/departments/{department}/batches/{batch}/sections', [SectionController::class, 'index'])->middleware('permission:' . PermissionEnum::VIEW_SECTION->value);
    Route::post('/departments/{department}/batches/{batch}/sections', [SectionController::class, 'store'])->middleware('permission:' . PermissionEnum::CREATE_SECTION->value);
    Route::get('/departments/{department}/batches/{batch}/sections/{section}', [SectionController::class, 'show'])->middleware('permission:' . PermissionEnum::VIEW_SECTION->value);
    Route::put('/departments/{department}/batches/{batch}/sections/{section}', [SectionController::class, 'update'])->middleware('permission:' . PermissionEnum::UPDATE_SECTION->value);
    Route::delete('/departments/{department}/batches/{batch}/sections/{section}', [SectionController::class, 'destroy'])->middleware('permission:' . PermissionEnum::DELETE_SECTION->value);
});


// Batch Course Semester Routes
// course allocation for the semesters of a batch
Route::group(['middleware' => ['auth:sanctum']], function () {



    Route::get(
        '/batches/{batch}/semesters/{semester}/courses',
        [CourseAllocationController::class, 'index']
    )->middleware('permission:' . PermissionEnum::VIEW_ALLOCATED_COURSE->value);

    Route::get(
        '/batches/{batch}/available-courses',
        [CourseAllocationController::class, 'available_courses']
    )->middleware('permission:' . PermissionEnum::VIEW_ALLOCATED_COURSE->value);

    Route::post('/batches/{batch}/semesters/{semester}/allocate-courses', [CourseAllocationController::class, 'allocate_courses'])->middleware('permission:' . PermissionEnum::ALLOCATE_COURSE->value);

    Route::post('/batches/{batch}/semesters/{semester}/deallocate-courses', [CourseAllocationController::class, 'deallocate_courses'])->middleware('permission:' . PermissionEnum::DEALLOCATE_COURSE->value);

    Route::post('/batches/{batch}/semesters/{semester}/sync-courses', [CourseAllocationController::class, 'sync_courses'])->middleware('permission:' . PermissionEnum::ALLOCATE_COURSE->value);
});


Route::group(['middleware' => ['auth:sanctum', 'role:' . RoleEnum::STUDENT->value]], function () {

    // Route::post('/registrations/semester-registration', [SemesterRegistrationController::class, 'allocatedCourses']);
    // for students

    Route::get(
        '/semesters/{semester}/registration-status',
        [SemesterRegistrationController::class, 'registration_status']
    );



    Route::post('/registrations/semester-registrations', [SemesterRegistrationController::class, 'registerCourses']);
});



// Teacher Assignment Routes

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get(
        '/batches/{batch}/semesters/{semester}/sections/{section}/teacher-assignments',
        [TeacherAssignmentController::class, 'index']
    );

    Route::post(
        '/batches/{batch}/semesters/{semester}/sections/{section}/teacher-assignments',
        [TeacherAssignmentController::class, 'store']
    );

    Route::put(
        '/batches/{batch}/semesters/{semester}/sections/{section}/teacher-assignments/{teacherAssignment}',
        [TeacherAssignmentController::class, 'update']
    );
});
