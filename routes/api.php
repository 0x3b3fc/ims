<?php

use App\Http\Controllers\API\UsersController;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('login', [UsersController::class,'login'])->name('api.login');

Route::middleware('auth:sanctum')->group(function (){
    Route::controller(UsersController::class)->prefix('users')->group(function () {
        Route::get('/', 'index')->middleware('permission:users-list')->name('api.users.index');
        Route::post('/', 'store')->middleware('permission:users-create')->name('api.users.store');
        Route::get('/{user}', 'show')->middleware('permission:users-read')->name('api.users.show');
        Route::put('/{user}', 'update')->middleware('permission:users-update')->name('api.users.update');
        Route::delete('/{user}', 'destroy')->middleware('permission:users-delete')->name('api.users.destroy');
    });

    Route::post('logout', [UsersController::class,'logout'])->name('api.logout');
    Route::get('me', [UsersController::class,'profile'])->name('api.me');
});
