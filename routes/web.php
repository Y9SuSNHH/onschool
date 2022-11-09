<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\HomePageController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(
    [
        'prefix' => 'admin',
        'as'     => 'admin.'
    ], static function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::get('/', [HomePageController::class, 'index'])->name('index');
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');
    Route::group(
        [
            'prefix' => 'users',
            'as'     => 'users.'
        ], static function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('create', [UserController::class, 'create'])->name('create');
        Route::get('edit/{id?}', [UserController::class, 'edit'])->name('edit');
    });
    Route::group(
        [
            'prefix' => 'students',
            'as'     => 'students.'
        ], static function () {
        Route::get('/', [StudentController::class, 'index'])->name('index');
        Route::get('create', [StudentController::class, 'create'])->name('create');
        Route::get('show', [StudentController::class, 'show'])->name('show');
        Route::get('edit/{id?}', [StudentController::class, 'edit'])->name('edit');
    });
});

