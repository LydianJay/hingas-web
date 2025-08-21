<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthCtrl;
use App\Http\Controllers\ESP32API;
use App\Http\Controllers\Registration;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Records;

Route::get('/', [AuthCtrl::class, 'index'])->name('login');



Route::post('/login', [AuthCtrl::class, 'login'])->name('login.post');
Route::post('/logout', [AuthCtrl::class, 'logout'])->name('logout');


Route::middleware(['auth:web'])->group(function(){

    Route::get('/registration', [Registration::class, 'index'])->name('registration');
    Route::post('/registration/register', [Registration::class, 'register'])->name('register');
    Route::post('/registration/edit', [Registration::class, 'edit'])->name('edit_user');
    Route::get('/records/attendance', [Records::class, 'attendance'])->name('attendance');



    Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');
});
// =========================================================
// ESP32API 
//
// We cant use middleware because we cant handle (im lazy)
// CSRF that laravel uses
// =========================================================
    Route::post('/api/rfid', [ESP32API::class, 'rfid'])->name('rfid');
    

// =========================================================