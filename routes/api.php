<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\LoanHistoryController;

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

Route::post('login', [UserController::class, 'login']);
Route::post('users/create', [UserController::class, 'create']);
Route::get('users', [UserController::class, 'getAllUsers']);


Route::middleware('auth:api')->group(function () {
    // User routes
    Route::get('users/{id}', [UserController::class, 'getUserById']);
    Route::get('users/profile', [ProfileController::class, 'getProfile']);
    Route::get('users/driver-license-front/{id}', [UserController::class, 'getDriverLicenseFrontImage']);
    Route::get('users/driver-license-back/{id}', [UserController::class, 'getDriverLicenseBackImage']);

    Route::put('users/update', [UserController::class, 'update']);
    Route::delete('users/delete/{id}', [UserController::class, 'delete']);

    // Profile routes
    Route::put('profiles/update', [ProfileController::class, 'updateProfile']);
    Route::delete('profiles/delete', [ProfileController::class, 'deleteProfile']);

    // Card routes
    Route::get('cards', [CardController::class, 'getCards']);
    Route::post('cards', [CardController::class, 'addCard']);
    Route::put('cards/update/{id}', [CardController::class, 'updateCard']);
    Route::delete('cards/delete/{id}', [CardController::class, 'deleteCard']);

    // LoanHistory routes
    Route::get('loan-history', [LoanHistoryController::class, 'getLoanHistory']);
    Route::post('loan-history', [LoanHistoryController::class, 'addLoanHistory']);
    Route::put('loan-history/update/{id}', [LoanHistoryController::class, 'updateLoanHistory']);
    Route::delete('loan-history/delete/{id}', [LoanHistoryController::class, 'deleteLoanHistory']);
});





