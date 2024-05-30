<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\RCategoryController;
use App\Http\Controllers\RCourseController;
use App\Http\Controllers\Teacher\RTeacherController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AllStudentController;

use App\Http\Controllers\Api\Admin\AddStudentController;
use App\Http\Controllers\Api\SuperAdmin\AboutController;
use App\Http\Controllers\Api\SuperAdmin\AssessionController;
use App\Http\Controllers\Api\SuperAdmin\ContactUsController;
use App\Http\Controllers\Api\SuperAdmin\EventController;
use App\Http\Controllers\Api\SuperAdmin\GallerytController;
use App\Http\Controllers\Api\SuperAdmin\successStoryController;
use App\Http\Controllers\Api\SuperAdmin\AdmittedController;
use App\Http\Controllers\Api\SuperAdmin\DropoutStudentController;
use App\Http\Controllers\Api\SuperAdmin\ReviewController;
use App\Http\Controllers\Api\SuperAdmin\IncludeCostController;
use App\Http\Controllers\Api\SuperAdmin\DashboardController;
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

Route::get('/dashboard', [DashboardController::class, 'counting']);
Route::get('/student-ratio', [DashboardController::class, 'averageMonthlyAdmissions']);

Route::post('/privacy', [AboutController::class, 'privacyPolicy']);
Route::get('/show/privacy', [AboutController::class, 'show_privacy']);

Route::get('/show/all/student', [AllStudentController::class, 'Show_all_student']);
Route::get('/show/auth/student', [AllStudentController::class, 'auth_type_student']);
Route::get('/show/techer/student', [AllStudentController::class, 'teacher_type_student']);
Route::get('/student/details/{id}', [AllStudentController::class, 'student_details']);
Route::get('/student/delete/{id}', [AllStudentController::class, 'destroy']);
Route::post('/admit', [AllStudentController::class, 'addmitStudent']);
Route::get('/admitted/student', [AdmittedController::class, 'admittedStudent']);
Route::post('/admit/payment', [AdmittedController::class, 'admittedPayment']);
Route::get('/singel/addmit/student/{id}', [AdmittedController::class, 'singel_admitted_student']);
Route::post('/dropout/addmit/student', [AdmittedController::class, 'dropout_student']);
Route::get('/show/dropout/student', [DropoutStudentController::class, 'show_dropout_student']);
Route::post('/refund', [DropoutStudentController::class, 'store_refund']);

// ========================= Add student ============== //

Route::post('/add/student', [AddStudentController::class, 'addStudent']);

// ===================== Add Review ================= //

Route::resource('reviews', ReviewController::class);

// ===================== ADD INCLUDE COST ================= //
Route::resource('/include/cost', IncludeCostController::class);

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



// ==================== CONTACT US =====================//

Route::post('/contacts', [ContactUsController::class, 'store']);  // Create
Route::get('/contacts', [ContactUsController::class, 'index']);  // Read (All)
Route::get('/contacts/{id}', [ContactUsController::class, 'show']);  // Read (Single)
Route::delete('/contacts/{id}', [ContactUsController::class, 'destroy']);



Route::middleware(['super.admin'])->group(function (){

    //====================== Super Admin Teacher ============================
    Route::resource('teachers',RTeacherController::class)->except('create','edit');

    Route::get('admin-show-leave-application',[RTeacherController::class,'showLeaveApplication']);
    Route::get('approve-leave-application',[RTeacherController::class,'approveLeaveRequest']);
    Route::get('reject-leave-application',[RTeacherController::class,'rejectLeaveRequest']);
});

Route::middleware(['mentor'])->group(function (){

    //===================== Trainer Dashboard ================================

    Route::post('request-leave-application',[TeacherDashboardController::class,'requestLeaveApplication']);
    Route::get('show-leave-application',[TeacherDashboardController::class,'showLeaveRequest']);

});


