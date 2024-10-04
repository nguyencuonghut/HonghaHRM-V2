<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\RecruitmentRequestController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;


/**Authentication */
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'handleLogin'])->name('handleLogin');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('forgot.password.get');
Route::post('/forgot-password', [LoginController::class, 'submitForgotPasswordForm'])->name('forgot.password.post');
Route::get('/reset-password/{token}', [LoginController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('/reset-password', [LoginController::class, 'submitResetPasswordForm'])->name('reset.password.post');


Route::group(['middleware'=>'auth:web'], function() {
    //Home
    Route::get('/', [HomeController::class, 'home'])->name('home');

    //Roles
    Route::post('/roles/import', [RoleController::class, 'import'])->name('roles.import');
    Route::get('/roles/data', [RoleController::class, 'anyData'])->name('roles.data');
    Route::resource('/roles', RoleController::class);

    //Users
    Route::post('/users/import', [UsersController::class, 'import'])->name('users.import');
    Route::get('/users/data', [UsersController::class, 'anyData'])->name('users.data');
    Route::resource('/users', UsersController::class);

    //Departments
    Route::get('/departments/get-division/{department_id}', [DepartmentController::class, 'getDivision'])->name('departments.getDivision');
    Route::post('/departments/import', [DepartmentController::class, 'import'])->name('departments.import');
    Route::get('/departments/data', [DepartmentController::class, 'anyData'])->name('departments.data');
    Route::resource('/departments', DepartmentController::class);

    //Divisions
    Route::post('/divisions/import', [DivisionController::class, 'import'])->name('divisions.import');
    Route::get('/divisions/data', [DivisionController::class, 'anyData'])->name('divisions.data');
    Route::resource('/divisions', DivisionController::class);

    //Positions
    Route::post('/positions/import', [PositionController::class, 'import'])->name('positions.import');
    Route::get('/positions/data', [PositionController::class, 'anyData'])->name('positions.data');
    Route::resource('/positions', PositionController::class);

    //RecruitmentRequest
    Route::post('/recruitment_requests/approve/{id}', [RecruitmentRequestController::class, 'approve'])->name('recruitment_requests.approve');
    Route::post('/recruitment_requests/review/{id}', [RecruitmentRequestController::class, 'review'])->name('recruitment_requests.review');
    Route::get('/recruitment_requests/data', [RecruitmentRequestController::class, 'anyData'])->name('recruitment_requests.data');
    Route::resource('/recruitment_requests', RecruitmentRequestController::class);
});
