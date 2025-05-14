<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group([
    'middleware'=> 'api',
    'prefix'=> 'auth'
], function($router){
    Route::post("/login", [AuthController::class, 'login']);
    Route::post("/register", [AuthController::class, 'register']);
    Route::post("/logout", [AuthController::class, 'logout']);
    Route::post("/refresh", [AuthController::class, 'refresh']);
    Route::post("/user-profile", [AuthController::class, 'userProfile']);
});


// Brands CRUD
Route::controller(BrandsController::class)->group(function(){
    Route::get('index', 'index');
    Route::get('show/{id}', 'show');
    Route::post('store', 'store');
    Route::put('update_brand/{id}', 'update_brand');
    Route::delete('delete_brand/{id}', 'delete_brand');
});

# Category CRUD
Route::controller(CategoryController::class)->group(function() {
    Route::get('index', 'index');
    Route::get('show/{id}', 'show');
    Route::post('store', 'store');
    Route::put('update_category/{id}', 'update_category');
    Route::delete('delete_category/{id}', 'delete_category');
});
