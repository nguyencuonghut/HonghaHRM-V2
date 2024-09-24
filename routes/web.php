<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;


/**Authentication */
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'handleLogin'])->name('handleLogin');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['middleware'=>'auth:web'], function() {
    //Home
    Route::get('/', [HomeController::class, 'home'])->name('home');

    //Users
    Route::post('users/import', [UsersController::class, 'import'])->name('users.import');
    Route::get('/users/data', [UsersController::class, 'anyData'])->name('users.data');
    Route::resource('/users', UsersController::class);
    });
