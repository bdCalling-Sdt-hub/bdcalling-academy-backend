<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\RCategoryController;
use App\Http\Controllers\RCourseController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication

Route::group([
    ['middleware' => 'auth:api']
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/email-verified', [AuthController::class, 'emailVerified']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/profile', [AuthController::class, 'loggedUserData']);
    Route::post('/forget-pass', [AuthController::class, 'forgetPassword']);
    Route::post('/verified-checker', [AuthController::class, 'emailVerifiedForResetPass']);
    Route::post('/reset-pass', [AuthController::class, 'resetPassword']);
    Route::post('/update-pass', [AuthController::class, 'updatePassword']);
    Route::put('/profile/edit/{id}', [AuthController::class, 'editProfile']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
});




Route::resource('categories',RCategoryController::class)->except('create','edit');
Route::resource('courses',RCourseController::class)->except('create','edit');

//module add
Route::post('add-module',[ModuleController::class,'addModule']);
Route::get('show-module',[ModuleController::class,'showModule']);

//teacher add

//create - batch

Route::get('create-batch',[TestController::class,'createBatch']);

Route::get('do-awesome-service',[TestController::class,'doAwesome']);
Route::get('test-service',[TestController::class,'testService']);
