<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserDetailController;
use App\Http\Controllers\AttendanceController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/', [UserDetailController::class, 'index'])->name('users.index');
Route::get('/user/create', [UserDetailController::class, 'create'])->name('users.create');
Route::post('/user/store', [UserDetailController::class, 'store'])->name('users.store');

Route::get('/attendance/{user}', [AttendanceController::class, 'calendar'])->name('attendance.calendar');
Route::post('/attendance/mark', [AttendanceController::class, 'mark'])->name('attendance.mark');

// routes/web.php
Route::get('/salary/calculate/{user}', [AttendanceController::class, 'calculateSalary'])->name('salary.calculate');

Route::get('/salary/summary/{id}', [AttendanceController::class, 'showSalary'])->name('salary.summary');
Route::get('/salary/{id}', [AttendanceController::class, 'showSalary'])->name('salary.result');
