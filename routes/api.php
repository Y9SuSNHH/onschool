<?php

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

//Route::middleware('auth:api')->get('/users', function (Request $request) {
//    return $request->users();
//});
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
        Route::get('/{id?}', [UserController::class, 'profile'])->name('profile');
        Route::get('list/{page?}', [UserController::class, 'list'])->name('list');
        Route::put('edit/{id?}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id?}', [UserController::class, 'destroy'])->name('destroy');
    });
});
