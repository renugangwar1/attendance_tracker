<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserDetailController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SalaryController;
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


// Public routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.page');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register.page');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect root to dashboard

// Protected routes
Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
    return redirect()->route('users.index');
});

 Route::get('/users', [UserDetailController::class, 'index'])->name('users.index');


    Route::get('/user/create', [UserDetailController::class, 'create'])->name('users.create');
    Route::post('/user/store', [UserDetailController::class, 'store'])->name('users.store');

    Route::get('/attendance/{user}', [AttendanceController::class, 'calendar'])->name('attendance.calendar');
    Route::post('/attendance/mark', [AttendanceController::class, 'mark'])->name('attendance.mark');
    Route::post('/attendance/unmark', [AttendanceController::class, 'unmark'])->name('attendance.unmark');


    Route::get('/salary/calculate/{user}', [AttendanceController::class, 'calculateSalary'])->name('salary.calculate');
    Route::get('/salary/summary/{id}', [AttendanceController::class, 'showSalary'])->name('salary.summary');
   
    Route::get('/salary/{id}', [AttendanceController::class, 'showSalary'])->name('salary.show');
    Route::get('/salary/{id}/download', [AttendanceController::class, 'download'])->name('salary.download');
Route::get('/users/{id}/edit', [UserDetailController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserDetailController::class, 'update'])->name('users.update');
Route::post('/salary/credit/{user}', [AttendanceController::class, 'toggleCredit'])->name('salary.toggleCredit');



// Salary
Route::get('/salary', [SalaryController::class, 'index'])->name('salary.index'); // Dashboard page
Route::get('/salary/manage/{id}', [SalaryController::class, 'manage'])->name('salary.manage'); // Manage button



Route::post('/salary/{salary}/expense', [SalaryController::class, 'addExpense'])->name('salary.addExpense');
Route::delete('/salary/expense/{id}', [SalaryController::class, 'deleteExpense'])->name('salary.deleteExpense');

});


