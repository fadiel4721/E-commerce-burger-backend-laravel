<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;




Route::get('/', function () {
    return view('welcome');
});

Route::get('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('login', [LoginController::class, 'login'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'prosesLogin'])->name('login.proses');