<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProspectusController;
use App\Http\Controllers\PasswordResetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/get-users', [UserController::class, 'getUsers']);
    Route::post('/add-user', [UserController::class, 'addUser']);
    Route::put('/edit-user/{id}', [UserController::class, 'editUser']);
    Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser']);

    Route::post('/change-password', [AuthenticationController::class, 'changePassword']);

    Route::get('/get-students', [StudentController::class, 'getStudents']);
    Route::post('/add-student', [StudentController::class, 'addStudent']);
    Route::put('/edit-student/{id}', [StudentController::class, 'editStudent']);
    Route::delete('/delete-student/{id}', [StudentController::class, 'deleteStudent']);

    Route::get('/get-roles', [RoleController::class, 'getRoles']);
    Route::post('/add-role', [RoleController::class, 'addRole']);
    Route::put('/edit-role/{id}', [RoleController::class, 'editRole']);
    Route::delete('/delete-role/{id}', [RoleController::class, 'deleteRole']);

    Route::get('/get-statuses', [StatusController::class, 'getStatuses']);
    Route::post('/add-status', [StatusController::class, 'addStatus']);
    Route::put('/edit-status/{id}', [StatusController::class, 'editStatus']);
    Route::delete('/delete-status/{id}', [StatusController::class, 'deleteStatus']);

    Route::get('/get-programs', [ProgramController::class, 'getPrograms']);
    Route::post('/add-program', [ProgramController::class, 'addProgram']);
    Route::put('/edit-program/{id}', [ProgramController::class, 'editProgram']);
    Route::delete('/delete-program/{id}', [ProgramController::class, 'deleteProgram']);

    Route::get('/get-courses', [CourseController::class, 'getCourses']);
    Route::post('/add-course', [CourseController::class, 'addCourse']);
    Route::put('/edit-course/{id}', [CourseController::class, 'editCourse']);
    Route::delete('/delete-course/{id}', [CourseController::class, 'deleteCourse']);

    Route::get('/get-enrollments', [EnrollmentController::class, 'getEnrollments']);
    Route::post('/add-enrollment', [EnrollmentController::class, 'addEnrollment']);
    Route::put('/edit-enrollment/{id}', [EnrollmentController::class, 'editEnrollment']);
    Route::delete('/delete-enrollment/{id}', [EnrollmentController::class, 'deleteEnrollment']);
    Route::post('/approve-enrollment/{enrollment_id}', [EnrollmentController::class, 'approveEnrollment']);

    Route::get('/get-prospectuses', [ProspectusController::class, 'getProspectuses']);
    Route::post('/add-prospectus', [ProspectusController::class, 'addProspectus']);
    Route::put('/edit-prospectus/{id}', [ProspectusController::class, 'editProspectus']);
    Route::delete('/delete-prospectus/{id}', [ProspectusController::class, 'deleteProspectus']);

    Route::post('/logout', [AuthenticationController::class, 'logout']);
});

// Password reset routes
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [PasswordResetController::class, 'reset']);
