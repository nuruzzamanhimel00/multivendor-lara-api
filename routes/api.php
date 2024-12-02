<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\api\CompanyController;
use App\Http\Controllers\api\admin\UserController;
use App\Http\Controllers\api\admin\auth\AuthController;
use App\Http\Controllers\api\admin\plan\PlanController;
use App\Http\Controllers\api\user\auth\UserAuthController;
use App\Http\Controllers\api\admin\settings\SettingController;

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
    return $request->user()->load(['loginUser']);
});
//user auth
Route::post('/login',[UserAuthController::class,'login']);
Route::post('/register',[UserAuthController::class,'register']);
//admin auth
Route::post('/admin/login',[AuthController::class,'login']);
Route::post('/admin/register',[AuthController::class,'register']);
// Route::post('/company/{id}', [CompanyController::class, 'companyUpdate']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    //login as
    Route::middleware(['verifiedAdmin'])->group(function () {
        Route::post('/impersonate/{ownerId}', [AuthController::class, 'impersonateOwner']);
        Route::post('/stop-impersonation/', [AuthController::class, 'stopImpersonation']);
    });

    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('/company/{id}', [CompanyController::class, 'getCompany']);
    Route::post('/company/{id}', [CompanyController::class, 'companyUpdate']);
    Route::post('/selected-company-delete', [CompanyController::class, 'selectedCompanyDelete']);
    Route::apiResource('companies', CompanyController::class);
    //SETTINGS
    Route::get('/system-settings/get', [SettingController::class, 'getAllSystemSetting']);
    Route::post('/system-settings/set', [SettingController::class, 'setSystemSetting']);

    Route::apiResource('users', UserController::class);
    Route::post('user-status-update', [UserController::class,'statusUpdate']);
    //PLAN
    Route::apiResource('plans', PlanController::class);
    Route::post('plans/update/{id}', [PlanController::class,'planUpdate']);
    Route::post('plan-enable-ordering', [PlanController::class,'planEnableOrdering']);
    Route::post('plan-period-change', [PlanController::class,'planPeriodChange']);
    Route::post('/selected-plan-delete', [PlanController::class, 'selectedPlanDelete']);

  });
//temp file upload
//********** Ajax Request */
Route::prefix('companies')->group(function () {
    Route::post('ajax/logo-update', [CompanyController::class,'logoUpdate']);
});


Route::post('/payment/initiate', [PaymentController::class, 'initiatePayment']);
Route::post('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::post('/payment/fail', [PaymentController::class, 'paymentFail'])->name('payment.fail');
Route::post('/payment/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');

