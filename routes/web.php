<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;

Route::prefix( 'v1' )->group( function () {

    /*
    |--------------------------------------------------------------------------
    | 🔐 Auth Routes (v1)
    |--------------------------------------------------------------------------
     */
    Route::post( '/user-registration', [UserController::class, 'userRegistration'] )->name( 'userRegistration' );
    Route::post( '/user-login', [UserController::class, 'userLogin'] )->name( 'userLogin' );
    Route::post( '/send-otp', [UserController::class, 'sendOTP'] )->name( 'sendOTP' );
    Route::post( '/verify-otp', [UserController::class, 'verifyOTP'] )->name( 'verifyOTP' );
    Route::post( '/reset-password', [UserController::class, 'resetPassword'] )
        ->middleware( [TokenVerificationMiddleware::class] )
        ->name( 'resetPassword' );

    /*
    |--------------------------------------------------------------------------
    | 👤 User Profile Routes (v1)
    |--------------------------------------------------------------------------
     */
    Route::get( '/user-profile-details', [UserController::class, 'userProfileDetails'] )
        ->middleware( [TokenVerificationMiddleware::class] )
        ->name( 'userProfileDetails' );

    Route::patch( '/update-user-profile', [UserController::class, 'updateUserProfile'] )
        ->middleware( [TokenVerificationMiddleware::class] )
        ->name( 'updateUserProfile' );
} );