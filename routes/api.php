<?php

use App\Http\Controllers\Api\SouperAdmin\AboutController;
use App\Http\Controllers\Api\SouperAdmin\AssessionController;
use App\Http\Controllers\Api\SouperAdmin\SuccessStoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | API Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register API routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "api" middleware group. Make something great!
 * |
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

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
Route::get('test', [SuccessStoryController::class, 'test']);
