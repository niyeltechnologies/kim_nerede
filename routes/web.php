<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentCourseOperationsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [StudentCourseOperationsController::class, 'getDashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/students', [StudentCourseOperationsController::class, 'getStudentList'])->middleware(['auth', 'verified'])->name('student_list.show');

Route::get('/student/add', [StudentCourseOperationsController::class, 'addStudentScreen'])->middleware(['auth', 'verified'])->name('add_student.show');
Route::post('/student/add', [StudentCourseOperationsController::class, 'addStudentSave'])->middleware(['auth', 'verified'])->name('add_student.save');
Route::get('/student/{id}/auhtority/add', [StudentCourseOperationsController::class, 'addStudentAuthorityScreen'])->middleware(['auth', 'verified'])->name('add_student_authority.show');
Route::post('/student/{id}/auhtority/add', [StudentCourseOperationsController::class, 'addStudentAuthoritySave'])->middleware(['auth', 'verified'])->name('add_student_authority.save');


Route::get('/courseschedules', [StudentCourseOperationsController::class, 'getStudentCourseList'])->middleware(['auth', 'verified'])->name('student_course_list.show');

Route::get('/courseschedule/add', [StudentCourseOperationsController::class, 'addStudentCourseScreen'])->middleware(['auth', 'verified'])->name('add_student_course.show');
Route::post('/courseschedule/add', [StudentCourseOperationsController::class, 'addStudentCourseSave'])->middleware(['auth', 'verified'])->name('add_student_course.save');

Route::get('/courseschedule/{id}/day/add', [StudentCourseOperationsController::class, 'addStudentCourseDayScreen'])->middleware(['auth', 'verified'])->name('add_student_course_day.show');
Route::post('/courseschedule/{id}/day/add', [StudentCourseOperationsController::class, 'addStudentCourseDaySave'])->middleware(['auth', 'verified'])->name('add_student_course_day.save');

Route::get('/courses', [StudentCourseOperationsController::class, 'getCourseList'])->middleware(['auth', 'verified'])->name('course_list.show');

Route::get('/course/add', [StudentCourseOperationsController::class, 'addCourseScreen'])->middleware(['auth', 'verified'])->name('add_course.show');
Route::post('/course/add', [StudentCourseOperationsController::class, 'addCourseSave'])->middleware(['auth', 'verified'])->name('add_course.save');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
