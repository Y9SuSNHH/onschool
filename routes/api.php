<?php

use App\Http\Controllers\JwtController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('auth/login', [JwtController::class, 'login'])->name('auth.login');

Route::group([
    'middleware' => ['jwt.auth', 'permission', 'xss'],
], static function ($router) {
    Route::group(
        [
            'prefix' => 'auth',
            'as'     => 'auth.'
        ], static function () {
        Route::post('profile', [JwtController::class, 'profile'])->name('profile');
        Route::post('logout', [JwtController::class, 'logout'])->name('logout');
        Route::post('payload', [JwtController::class, 'payload'])->name('payload');
        Route::post('refresh', [JwtController::class, 'refresh'])->name('refresh');
    });
    Route::get('logs', [LogController::class, 'list'])->name('logs.list');
    Route::group(
        [
            'prefix' => 'users',
            'as'     => 'users.'
        ], static function () {
        Route::get('list', [UserController::class, 'list'])->name('list');
        Route::post('create', [UserController::class, 'store'])->name('store');
        Route::get('/{id?}', [UserController::class, 'show'])->name('show');
        Route::put('edit/{id?}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id?}', [UserController::class, 'destroy'])->name('destroy');
    });
    Route::group(
        [
            'prefix' => 'students',
            'as'     => 'students.'
        ], static function () {
        Route::get('list', [StudentController::class, 'list'])->name('list');
        Route::post('create', [StudentController::class, 'store'])->name('store');
        Route::get('/{id?}', [StudentController::class, 'show'])->name('show');
        Route::put('edit/{id?}', [StudentController::class, 'update'])->name('update');
        Route::delete('/{id?}', [StudentController::class, 'destroy'])->name('destroy');
    });
});
