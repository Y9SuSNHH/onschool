<?php

use App\Http\Controllers\UserController;
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
Route::get('/admin', static function () {
    return view('admin.welcome');
})->name('admin.welcome');
Route::get('/admin/users', static function () {
    return view('admin.user.index');
})->name('admin.users');
