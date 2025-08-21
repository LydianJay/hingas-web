<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthCtrl;
use App\Http\Controllers\ESP32API;
use App\Http\Controllers\Registration;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Records;
use App\Http\Controllers\Studio;


Route::get('/', [AuthCtrl::class, 'index'])->name('login');



Route::post('/login', [AuthCtrl::class, 'login'])->name('login.post');
Route::post('/logout', [AuthCtrl::class, 'logout'])->name('logout');


Route::middleware(['auth:web'])->group(function(){

    Route::get('/registration', [Registration::class, 'index'])->name('registration');
    Route::post('/registration/register', [Registration::class, 'register'])->name('register');
    Route::post('/registration/edit', [Registration::class, 'edit'])->name('edit_user');
    Route::post('/registration/enroll', [Registration::class, 'enroll'])->name('enroll');
    Route::get('/records/attendance', [Records::class, 'attendance'])->name('attendance');


    Route::get('/dance', [Studio::class, 'dance'])->name('dance');


    Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');


    // =================================================
    // APIS
    // =================================================
    Route::get('/registration/get_enrollment_details', [Registration::class, 'get_enrollment_details'])->name('get_enrollment_details');

    // ==================================================

});
// =========================================================
// ESP32API 
//
// We cant use middleware because we cant handle (im lazy)
// CSRF that laravel uses
// =========================================================
    Route::post('/api/rfid', [ESP32API::class, 'rfid'])->name('rfid');
    

// =========================================================