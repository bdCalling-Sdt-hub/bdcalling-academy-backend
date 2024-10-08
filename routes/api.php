<?php

use App\Http\Controllers\AddEmployeeController;
use App\Http\Controllers\Api\Student\QuizeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Batch\BatchSyncController;
use App\Http\Controllers\Calculation\CostController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentSslcommerzeController;
use App\Http\Controllers\PhoenixStudentController;
use App\Http\Controllers\PhoenixBatchController;
use App\Http\Controllers\RBatchController;
use App\Http\Controllers\RCategoryController;
use App\Http\Controllers\RCourseController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\Student\AdmitController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Student\StudentPaymentController;
use App\Http\Controllers\StudentDashboard\SStudentController;
use App\Http\Controllers\SuperAdmin\QuizController;
use App\Http\Controllers\Teacher\FeedbackController;
use App\Http\Controllers\Teacher\MarkController;
use App\Http\Controllers\Teacher\RAssignmentController;
use App\Http\Controllers\Teacher\RoutineController;
use App\Http\Controllers\Teacher\RTeacherController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherPaymentController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AllStudentController;
use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\NotificationsController;

use App\Http\Controllers\Api\Admin\AddStudentController;
use App\Http\Controllers\Api\SuperAdmin\AboutController;
use App\Http\Controllers\Api\SuperAdmin\AssessionController;
use App\Http\Controllers\Api\SuperAdmin\ContactUsController;
use App\Http\Controllers\Api\SuperAdmin\EventController;
use App\Http\Controllers\Api\SuperAdmin\GallerytController;
use App\Http\Controllers\Api\SuperAdmin\successStoryController;
use App\Http\Controllers\Api\SuperAdmin\AdmittedController;
use App\Http\Controllers\Api\SuperAdmin\DropoutStudentController;
use App\Http\Controllers\Api\SuperAdmin\SendSMScontroller;
use App\Http\Controllers\Api\SuperAdmin\ReviewController;
use App\Http\Controllers\Api\SuperAdmin\IncludeCostController;
use App\Http\Controllers\Api\SuperAdmin\DashboardController;
use App\Http\Controllers\Api\Student\StudentDashbordController;

use App\Http\Controllers\Api\WebApi\FreSemenarController;
use App\Http\Controllers\TrainerReviewController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;


// Authentication

Route::group([
    ['middleware' => 'auth:api']
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/email-verified/{token}', [AuthController::class, 'emailVerifiedOtp'])->name('verify.email');
    Route::post('/email-verified', [AuthController::class, 'emailVerified']);
//    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
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
Route::post('/update/admit', [AllStudentController::class, 'updateStudent']);
Route::get('/admitted/student', [AdmittedController::class, 'admittedStudent']);
Route::post('/admit/payment', [AdmittedController::class, 'admittedPayment']);
Route::get('/singel/addmit/student/{id}', [AdmittedController::class, 'singel_admitted_student']);
Route::post('/dropout/addmit/student', [AdmittedController::class, 'dropout_student']);
Route::get('/show/dropout/student', [DropoutStudentController::class, 'show_dropout_student']);
//Route::post('/refund', [DropoutStudentController::class, 'store_refund']);
Route::get('/show-event-student', [AllStudentController::class, 'event_type_student']);

// ========================= Add student ============== //

Route::post('/add/student', [AddStudentController::class, 'addStudent']);

// ===================== Add Review ================= //

// ===================== ADD INCLUDE COST ================= //
Route::resource('/include/cost', IncludeCostController::class);

// ============ About ================//

Route::get('/show/about', [AboutController::class, 'show_about']);

Route::post('/update/about', [AboutController::class, 'updateAbout']);

// ============ Terms ================//

Route::get('/show/terms', [AboutController::class, 'show_terms']);
Route::post('/update/terms', [AboutController::class, 'terms_condition']);

// ========================= ASSESSION ======================== //

// with web api

Route::resource('/assession', AssessionController::class);
Route::resource('/success/story', successStoryController::class);



// ==================== CONTACT US =====================//

Route::post('/contacts', [ContactUsController::class, 'store']);  // Create
Route::get('/contacts', [ContactUsController::class, 'index']);  // Read (All)
Route::get('/contacts/{id}', [ContactUsController::class, 'show']);  // Read (Single)
Route::delete('/contacts/{id}', [ContactUsController::class, 'destroy']);

// --------------- Join free semenar -------------------- //

Route::post('/store-semenar', [FreSemenarController::class, 'store']);
Route::get('/show-semenar', [FreSemenarController::class, 'show_semenar']);
Route::get('/destry-semenar/{id}', [FreSemenarController::class, 'destroy']);

// ------------------ Subscriber ----------------------- //

Route::post('/store-subscriber', [FreSemenarController::class, 'subscrib_store']);
Route::get('/show-subscriber', [FreSemenarController::class, 'show_subscriber']);
Route::get('/destry-subscriber/{id}', [FreSemenarController::class, 'destroy_subscriber']);


Route::middleware(['super.admin','auth:api'])->group(function (){

    //===================== Show Batch wise teacher--------------------
    Route::get('/show-assign-module',[RTeacherController::class,'showAssignModule']);

    //====================== Super Admin Teacher ============================
    Route::resource('teachers',RTeacherController::class)->except('create','edit','update');

    Route::get('admin-show-leave-application',[RTeacherController::class,'showLeaveApplication']);
    Route::post('approve-leave-application',[RTeacherController::class,'approveLeaveRequest']);
    Route::post('reject-leave-application',[RTeacherController::class,'rejectLeaveRequest']);

    //====================== Manage Admins / Super admins ====================================
    Route::resource('admins',AddEmployeeController::class)->except('create','edit');
    Route::get('show-super-admin',[AddEmployeeController::class,'showSuperAdmin']);

    //====================== Teachers Payment ====================================
    Route::post('add-teacher-salary',[CostController::class,'addTeacherSalary']);

    //====================== batch ==================================
    Route::resource('batches',RBatchController::class)->except('create','edit','show');
    Route::resource('phoenix-batches',PhoenixBatchController::class);
    Route::get('show-phoenix-students',[PhoenixStudentController::class,'showPhoenixStudent']);

    //==================== Quize ==============================//
    //student and super admin show quize//
    Route::resource('quize',QuizeController::class);

    Route::post('update-quiz',[QuizController::class,'ModuleWiseQuizUpdate']);

    //===================== Wallet =============================

    Route::get('/earnings',[WalletController::class,'earning']);

});

Route::middleware(['mentor','auth:api'])->group(function (){

    Route::get('teacher-batch',[RBatchController::class,'teacherBatch']);

    Route::get('teacher-base-student',[TeacherDashboardController::class,'teacherBaseStudent']);

    //===================== Trainer Dashboard ================================
    Route::post('request-leave-application',[TeacherDashboardController::class,'requestLeaveApplication']);
    Route::get('show-leave-application',[TeacherDashboardController::class,'showLeaveRequest']);

    Route::resource('/trainer-reviews', TrainerReviewController::class)->only('show');

    Route::get('teacher-dashboard',[TeacherDashboardController::class,'teacherDashboard']);

});

Route::middleware(['student'])->group(function (){
    Route::get('/student-counting', [StudentDashbordController::class, 'counting_student_info']);
    Route::get('/all-course', [StudentDashbordController::class, 'all_course']);
    Route::get('/course-modul-video/{id}', [StudentDashbordController::class, 'course_modul_video']);

    Route::post('/examination-test', [StudentDashbordController::class, 'exam_test_ans']);

    //============================ Student Dashboard ===========================
    Route::get('/show-student-feedback',[FeedbackController::class,'showFeedback']);


    //enrolled courses
    Route::get('/enrolled-courses', [SStudentController::class, 'enrolledCourses']);

    Route::resource('reviews', ReviewController::class)->only('index','store');

    Route::resource('/trainer-reviews', TrainerReviewController::class)->only('store');

    Route::get('/show-student-certificate',[CertificateController::class,'showCertificate']);



});


//============================== Teachers Payment ========================================================
Route::post('/teacher-payments',[TeacherPaymentController::class,'teacherPayment']);
Route::post('/teacher-payments-update/{id}',[TeacherPaymentController::class,'teacherPaymentUpdate']);
Route::get('/show-transactions',[TeacherPaymentController::class,'showAllTransactionByTeacher']);

Route::post('/send-sms',[SendSMScontroller::class,'send_sms']);

//==============================Sync Batch======================================
Route::post('/batch-teachers',[BatchSyncController::class,'syncBatch']);

Route::post('/admit-student',[AdmitController::class,'admitStudent']);

//Route::get('/show-admit-student/v2',[AdmitController::class,'showAdmitStudentV2']);
Route::get('/dropout-student',[AdmitController::class,'dropOutStudent']);
Route::get('/show-dropout-student',[AdmitController::class,'showDropOutStudent']);

//=================================Student Payment======================================
Route::post('/student-payment',[StudentPaymentController::class,'admittedPayment']);
Route::get('/show-student-payment',[StudentPaymentController::class,'showSingleStudentPaymentHistory']);


///====================== Website Api's =========================================

Route::get('/filter-courses',[WebsiteController::class,'filterCourse']);
Route::get('/popular-courses',[WebsiteController::class,'popularCourses']);

//==========================Student Mark Assign========================
Route::post('/assign-mark',[MarkController::class,'studentMark']);
Route::post('/assign-mark/{id}',[MarkController::class,'updateStudentMark']);
Route::get('/show-assign-mark',[MarkController::class,'showStudentMark']);

//-------------------------- Notificatins ------------------- //

Route::get('/show-notification',[NotificationsController::class,'notifications']);
Route::post('/mark-as-read/{id}',[NotificationsController::class,'markAsRead']);

Route::get('/delete-notification/{id}',[NotificationsController::class,'destroy']);

//====================================Payment =========================================

//sslcommerze payment route
Route::post('/pay', [PaymentSslcommerzeController::class, 'index']);
Route::post('/coupon-discount', [PaymentSslcommerzeController::class, 'discountCouponCode']);
Route::post('/success', [PaymentSslcommerzeController::class, 'success']);
Route::post('/fail', [PaymentSslcommerzeController::class, 'fail']);
Route::post('/cancel', [PaymentSslcommerzeController::class, 'cancel']);
Route::post('/ipn', [PaymentSslcommerzeController::class, 'ipn']);

Route::middleware(['admin','auth:api'])->group(function (){
    Route::get('all-reviews',[ReviewController::class,'allReviews']);
    Route::resource('reviews', ReviewController::class)->only('destroy');
    Route::resource('/gallery', GallerytController::class);
    Route::resource('/event', EventController::class);

    Route::get('/successful-student',[AdmitController::class,'showSuccessfulStudent']);
    Route::post('/completed-student',[AdmitController::class,'completedStudent']);

    Route::resource('/trainer-reviews', TrainerReviewController::class)->only('index');

    Route::get('/publish-trainer-reviews/{id}', [TrainerReviewController::class, 'publishTrainerReview']);

    Route::get('/admin-notification', [NotificationController::class, 'adminNotification']);
    Route::get('/read-notification', [NotificationController::class, 'readNotificationById']);

    Route::post('/refund', [RefundController::class, 'refund']);
});

Route::middleware(['student.admin','auth:api'])->group(function (){
    Route::get('/show-quize-student/{id}', [StudentDashbordController::class, 'show_quize']);

    Route::resource('reviews', ReviewController::class)->only('update');

    Route::resource('/trainer-reviews', TrainerReviewController::class)->only('update');
});

Route::middleware(['mentor.admin','auth:api'])->group(function (){
    //============================= Student =====================================
    Route::resource('/students',StudentController::class)->except('create','edit','update');

    Route::resource('routines',RoutineController::class)->except('create','edit');

    Route::resource('attendances',AttendanceController::class)->except('create','edit');

    Route::get('/show-admit-student',[AdmitController::class,'showAdmitStudent']);
    // =========================add module =============================
    Route::post('add-module',[ModuleController::class,'addModule']);

    Route::get('show-module',[ModuleController::class,'showModule']);

    Route::post('update-module/{id}',[ModuleController::class,'updateModule']);

    Route::post('update-module-video/{id}',[ModuleController::class,'updateModuleVideo']);

    Route::post('update-quiz/{id}',[ModuleController::class,'updateQuiz']);


    Route::get('show-module/{id}',[ModuleController::class,'getSingleModule']);

    //Assignment

    Route::resource('/assignments', RAssignmentController::class)->except('create','edit');

    Route::resource('batches',RBatchController::class)->only('show');

    Route::resource('teachers',RTeacherController::class)->only('update');

    //================================ Follow Up Message ===================================
    Route::post('/follow-up-message',[FollowUpController::class,'followUpMessage']);

    //============================Student Feedback=========================================
    Route::resource('/feedbacks',FeedbackController::class)->except('edit','create');
});


Route::middleware(['student.mentor.admin','auth:api'])->group(function (){
    Route::resource('/students',StudentController::class)->only('update');

    Route::get('/read-notification/{id}',[NotificationsController::class,'readNotificationById']);

    Route::get('/notifications', [NotificationController::class, 'notification']);
});

//Phoenix Batch Student
Route::post('/admit-phoenix-student',[PhoenixStudentController::class,'admitPhoenixStudent']);
Route::post('/update-phoenix-student/{id}',[PhoenixStudentController::class,'updatePhoenixStudent']);
Route::get('/destroy-phoenix-student/{id}',[PhoenixStudentController::class,'destroyPhoenixStudent']);
Route::post('/application-phoenix',[PhoenixStudentController::class,'applicationForPhoenixBatch']);




