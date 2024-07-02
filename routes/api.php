<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserBioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserTransactionController;
use App\Models\Appointment;
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

Route::prefix('v1')->name('api.')->group(function () {
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::get('/google-link', [ClassController::class, 'getGoogleLink'])->name('getGoogleLink');
    Route::get('/google-callback', [ClassController::class, 'getGoogleCode']); // get code
    Route::post('/google-token', [ClassController::class, 'getGoogleToken']); // get code
    Route::post('/cache-appointment/{appointment_id}', [AppointmentController::class, 'cacheAppointment']); // get code
    Route::get('/get-cache-appointment', [AppointmentController::class, 'getCacheAppointment']); // get code
    Route::post('/google-meet', [ClassController::class, 'createMeeting']); // get code
});

Route::prefix('v1')->name('api.')->middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::post('/user-transactions/for-processing', [UserTransactionController::class, 'for_processing']);
    Route::post('/appointments/search-class/{appointment_id}', [AppointmentController::class, 'search_class']);
    Route::resources([
        'user-transactions' => UserTransactionController::class,
        'user-bios' => UserBioController::class,
        'appointments' => AppointmentController::class,
        'schedules' => ScheduleController::class,
        'classes' => ClassController::class,
        'reviews' => ReviewController::class,
        'tickets' => TicketController::class,
        'penalties' => PenaltyController::class,
    ]);
    Route::resource('users', UserController::class)->except(['show', 'index']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
