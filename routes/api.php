<?php

use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\SetupController;

//Route::post('/users', [UserController::class , 'store']);

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    });
});

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('profile')->group(function () {
        Route::post('/complete', [ProfileController::class, 'completeProfile'])
            ->name('completeProfile');
        // Route::post('/update', [ProfileController::class, 'updateProfile'])->name('completeProfile');
    });


    Route::get('/getGrades', [\App\Http\Controllers\Api\SetupController::class, 'getGrades']);
    Route::get('/getInterests', [\App\Http\Controllers\Api\SetupController::class, 'getInterests']);
    Route::get('/getLearningTopics', [\App\Http\Controllers\Api\SetupController::class, 'getLearningTopics']);

    //Route::post('/forgot-password', [PasswordController::class, 'forgotPassword'])->name('forgotPassword');
    //Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('resetPassword');

});
