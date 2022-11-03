<?php

use App\Http\Controllers\Admin\HomePageController;
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
    Route::get('/', [HomePageController::class, 'index'])->name('index');
    Route::group(
        [
            'prefix' => 'users',
            'as'     => 'users.'
        ], static function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('edit/{id?}', [UserController::class, 'edit'])->name('edit');
    });
});
