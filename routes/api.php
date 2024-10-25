<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\CompanyController;
use App\Http\Controllers\api\admin\auth\AuthController;
use App\Http\Controllers\api\user\auth\UserAuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//user auth
Route::post('/login',[UserAuthController::class,'login']);
Route::post('/register',[UserAuthController::class,'register']);
//admin auth
Route::post('/admin/login',[AuthController::class,'login']);
Route::post('/admin/register',[AuthController::class,'register']);
Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('companies', CompanyController::class);
  });
