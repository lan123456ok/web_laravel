<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Middleware\CheckLoginMiddleware;
use Illuminate\Support\Facades\Route;


Route::get('login',[AuthController::class,'login'])->name('login');
Route::post('login',[AuthController::class,'processLogin'])->name('process_login');
Route::get('register',[AuthController::class,'register'])->name('register');
Route::post('register',[AuthController::class,'processRegister'])->name('process_register');

Route::group([
    'middleware' => CheckLoginMiddleware::class,
], function () {
    Route::get('logout',[AuthController::class,'logout'])->name('logout');
    Route::resource('course', CourseController::class)->except([
        'show',
        'destroy',
    ]);
    Route::get('course/api', [CourseController::class, 'api'])->name('course.api');
    Route::get('course/api/name', [CourseController::class, 'apiName'])->name('course.api.name');
    Route::get('test', function () {
        return view('layout.master');
    });

    Route::resource('student', StudentController::class)->except([
        'show',
        'destroy'
    ]);
    Route::get('student/api', [StudentController::class, 'api'])->name('student.api');
    Route::group( [
        'middleware' => \App\Http\Middleware\CheckSuperAdminMiddleware::class,
    ], function(){
        Route::delete('course/{course}', [CourseController::class, 'destroy'])->name('course.destroy');
        Route::delete('student/{student}', [CourseController::class, 'destroy'])->name('student.destroy');
    });
});

//Route::group(['prefix'=> 'courses','as' => 'course.'], function (){
//    Route::get('/', [CourseController::class, 'index'])->name('index');
//    Route::get('/create', [CourseController::class, 'create'])->name('create');
//    Route::post('/store', [CourseController::class, 'store'])->name('store');
//    Route::get('/edit/{course}', [CourseController::class, 'edit'])->name('edit');
//    Route::put('/edit/{course}', [CourseController::class, 'update'])->name('update');
//    Route::delete('/destroy/{course}', [CourseController::class, 'destroy'])->name('destroy');
//});
