<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProductStockHistoryController;
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

Route::post('login', [UsersController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    // Users routes with permissions
    Route::controller(UsersController::class)->prefix('users')->group(function () {
        Route::get('/', 'index')->middleware('permission:users-list')->name('api.users.index');
        Route::post('/', 'store')->middleware('permission:users-create')->name('api.users.store');
        Route::get('/{user}', 'show')->middleware('permission:users-read')->name('api.users.show');
        Route::put('/{user}', 'update')->middleware('permission:users-update')->name('api.users.update');
        Route::delete('/{user}', 'destroy')->middleware('permission:users-delete')->name('api.users.destroy');
    });

    // Categories routes with permissions
    Route::controller(CategoryController::class)->prefix('categories')->group(function () {
        Route::get('/', 'index')->middleware('permission:categories-list')->name('api.categories.index');
        Route::post('/', 'store')->middleware('permission:categories-create')->name('api.categories.store');
        Route::get('/{category}', 'show')->middleware('permission:categories-read')->name('api.categories.show');
        Route::put('/{category}', 'update')->middleware('permission:categories-update')->name('api.categories.update');
        Route::delete('/{category}', 'destroy')->middleware('permission:categories-delete')->name('api.categories.destroy');
    });

    // products routes with permissions
    Route::controller(ProductController::class)->prefix('products')->group(function () {
        Route::get('/', 'index')->middleware('permission:products-list')->name('api.products.index');
        Route::post('/', 'store')->middleware('permission:products-create')->name('api.products.store');
        Route::get('/{product}', 'show')->middleware('permission:products-read')->name('api.products.show');
        Route::put('/{product}', 'update')->middleware('permission:products-update')->name('api.products.update');
        Route::delete('/{product}', 'destroy')->middleware('permission:products-delete')->name('api.products.destroy');
    });

    // Orders routes with permissions
    Route::controller(OrderController::class)->prefix('orders')->group(function () {
        Route::get('/', 'index')->middleware('permission:orders-list')->name('api.orders.index');
        Route::post('/', 'store')->middleware('permission:orders-create')->name('api.orders.store');
        Route::get('/{order}', 'show')->middleware('permission:orders-read')->name('api.orders.show');
        Route::put('/{order}', 'update')->middleware('permission:orders-update')->name('api.orders.update');
        Route::delete('/{order}', 'destroy')->middleware('permission:orders-delete')->name('api.orders.destroy');
    });

    Route::get('/sales-orders', [OrderController::class,'salesReport'])->middleware('permission:orders-list')->name('api.orders.sales');

    // Stock histories routes with permissions
    Route::controller(ProductStockHistoryController::class)->prefix('stock-history')->group(function () {
        Route::get('/', 'index')->middleware('permission:stock_history-list')->name('api.stock-history.index');
        Route::get('/{stockHistory}', 'show')->middleware('permission:stock_history-read')->name('api.stock-history.show');
    });

    Route::post('logout', [UsersController::class, 'logout'])->name('api.logout');
    Route::get('me', [UsersController::class, 'profile'])->name('api.me');
});
