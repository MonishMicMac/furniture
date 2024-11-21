<?php

use App\Http\Controllers\api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\LoginController; // Import the LoginController correctly

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Routes for LoginController
Route::post('/createLogin', [LoginController::class, 'store']);
Route::get('/logincheck', [LoginController::class, 'check_login']);

Route::get('/category', [CategoryController::class, 'showCategory']);
Route::get('/subcategory', [CategoryController::class, 'showSubCategory']);
Route::get('/product', [CategoryController::class, 'showProduct']);
Route::get('/liq_product', [CategoryController::class, 'show_liq_Product']);

Route::get('/app_banner', [CategoryController::class, 'show_app_banner']);

