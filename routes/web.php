<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MethodController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SchoolController;
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

    //Recruitment
    Route::post('/recruitments/approve/{recruitment}', [RecruitmentController::class, 'approve'])->name('recruitments.approve');
    Route::post('/recruitments/review/{recruitment}', [RecruitmentController::class, 'review'])->name('recruitments.review');
    Route::get('/recruitments/data', [RecruitmentController::class, 'anyData'])->name('recruitments.data');
    Route::resource('/recruitments', RecruitmentController::class);

    //Methods
    Route::get('/methods/data', [MethodController::class, 'anyData'])->name('methods.data');
    Route::resource('/methods', MethodController::class);

    //Plans
    Route::post('/plans/approve/{plan}', [PlanController::class, 'approve'])->name('plans.approve');
    Route::resource('/plans', PlanController::class);

    //Social Media
    Route::get('/channels/data', [ChannelController::class, 'anyData'])->name('channels.data');
    Route::resource('/channels', ChannelController::class);

    //Announcements
    Route::resource('/announcements', AnnouncementController::class);

    //Provinces
    Route::get('/provinces/data', [ProvinceController::class, 'anyData'])->name('provinces.data');
    Route::resource('/provinces', ProvinceController::class);

    //Districts
    Route::get('/districts/data', [DistrictController::class, 'anyData'])->name('districts.data');
    Route::resource('/districts', DistrictController::class);

    //Communes
    Route::post('/communes/import', [CommuneController::class, 'import'])->name('communes.import');
    Route::get('/communes/data', [CommuneController::class, 'anyData'])->name('communes.data');
    Route::resource('/communes', CommuneController::class);

    //School
    Route::post('/schools/import', [SchoolController::class, 'import'])->name('schools.import');
    Route::get('/schools/data', [SchoolController::class, 'anyData'])->name('schools.data');
    Route::resource('/schools', SchoolController::class);

});
