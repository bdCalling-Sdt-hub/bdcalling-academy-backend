<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\RCategoryController;
use App\Http\Controllers\RCourseController;
use App\Http\Controllers\Teacher\RTeacherController;
use App\Http\Controllers\TestController;

use App\Http\Controllers\Api\Admin\AddStudentController;
use App\Http\Controllers\Api\SuperAdmin\AboutController;
use App\Http\Controllers\Api\SuperAdmin\AssessionController;
use App\Http\Controllers\Api\SuperAdmin\ContactUsController;
use App\Http\Controllers\Api\SuperAdmin\EventController;
use App\Http\Controllers\Api\SuperAdmin\GallerytController;
use App\Http\Controllers\Api\SuperAdmin\successStoryController;

use Illuminate\Support\Facades\Route;


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

// ================SUPER ADMIN ===================//

Route::get('/show/privacy', [AboutController::class, 'show_privacy']);
Route::post('/privacy', [AboutController::class, 'privacyPolicy']);

// ============ About ================//

Route::get('/show/about', [AboutController::class, 'show_about']);
Route::post('/update/about', [AboutController::class, 'updateAbout']);

// ============ Terms ================//

Route::get('/show/terms', [AboutController::class, 'show_terms']);
Route::post('/update/terms', [AboutController::class, 'terms_condition']);

// ========================= ASSESSION ======================== //

Route::resource('/assession', AssessionController::class);
Route::resource('/success/story', successStoryController::class);
Route::resource('/event', EventController::class);
Route::resource('/gallery', GallerytController::class);

// ========================= Add student ============== //

Route::post('/add/student', [AddStudentController::class, 'addStudent']);

// ==================== CONTACT US =====================//

Route::post('/contacts', [ContactUsController::class, 'store']);  // Create
Route::get('/contacts', [ContactUsController::class, 'index']);  // Read (All)
Route::get('/contacts/{id}', [ContactUsController::class, 'show']);  // Read (Single)
Route::delete('/contacts/{id}', [ContactUsController::class, 'destroy']);



//====================== Teacher ============================

Route::resource('teachers',RTeacherController::class)->except('create','edit');
