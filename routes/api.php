<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SubCategoryController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp/{id}', [AuthController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/verify-login-otp/{id}', [AuthController::class, 'verifyLoginOtp'])->name('verify.login.otp');

Route::middleware('auth:sanctum')->group(function () {

    /** Category Routes */
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/createCategory', [CategoryController::class, 'store']);
    Route::post('/updateCategory/{id}', [CategoryController::class, 'update']);
    Route::get('/category/{id}', [CategoryController::class, 'show']);
    Route::delete('/deleteCategory/{id}', [CategoryController::class, 'destroy']);

    /** Sub Category Routes */
    Route::get('/subCategories', [SubCategoryController::class, 'index']);
    Route::post('/createSubCategory', [SubCategoryController::class, 'store']);
    Route::post('/updateSubCategory/{id}', [SubCategoryController::class, 'update']);
    Route::get('/subCategory/{id}', [SubCategoryController::class, 'show']);
    Route::delete('/deleteSubCategory/{id}', [SubCategoryController::class, 'destroy']);

    /** Product Routes */
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/createProduct', [ProductController::class, 'store']);
    Route::post('/updateProduct/{id}', [ProductController::class, 'update']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    Route::delete('/deleteProduct/{id}', [ProductController::class, 'destroy']);

    Route::post('/logout', [AuthController::class, 'logout']);
});