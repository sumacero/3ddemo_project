<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);

Route::get('/sim', function () {
    return view('sim');
});

Route::get('/sim2', function () {
    return view('sim2');
});

