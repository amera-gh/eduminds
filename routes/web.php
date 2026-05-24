<?php

use App\Http\Controllers\AchievmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\skillController;
use App\Http\Controllers\StudentController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::resource('users', UserController::class);
// Route::resource('achievments', AchievmentController::class);
// Route::resource('grades', GradeController::class);
// Route::resource('levels', LevelController::class);
// Route::resource('skills', skillController::class);
// Route::resource('students', StudentController::class);

