<?php

use App\Http\Controllers\JwtController;
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

Route::post('login', [JwtController::class, 'login'])->name('login');

Route::group([
    'middleware' => 'jwt.auth',
], static function ($router) {
    Route::post('profile', [JwtController::class, 'profile'])->name('profile');
    Route::post('logout', [JwtController::class, 'logout'])->name('logout');
    Route::group(
        [
            'prefix' => 'admin',
            'as'     => 'admin.'
        ], static function () {
        Route::group(
            [
                'prefix' => 'users',
                'as'     => 'users.'
            ], static function () {
            Route::get('list', [UserController::class, 'list'])->name('list');
            Route::post('create', [UserController::class, 'store'])->name('store');
            Route::get('/{id?}', [UserController::class, 'show'])->name('show');
            Route::get('update-active/{id?}', [UserController::class, 'updateActive'])->name('update.active');
            Route::put('edit/{id?}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id?}', [UserController::class, 'destroy'])->name('destroy')->middleware('jwt.admin');
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
            Route::delete('/{id?}', [StudentController::class, 'destroy'])->name('destroy')->middleware('jwt.admin');
        });
    });
});
