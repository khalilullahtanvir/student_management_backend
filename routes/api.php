<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentController;
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


Route::post('/register', [StudentController::class, 'register']);
Route::post('/login', [StudentController::class, 'login']);
Route::post('/logout', [StudentController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('/courses', CourseController::class);
Route::apiResource('/enrollments', EnrollmentController::class);
Route::apiResource('/payments', PaymentController::class);