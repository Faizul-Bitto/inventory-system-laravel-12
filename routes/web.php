<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post( "/user-registration", [UserController::class, "userRegistration"] )->name( "userRegistration" );
Route::post( "/user-login", [UserController::class, "userLogin"] )->name( "userLogin" );