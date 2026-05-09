<?php

use Illuminate\Support\Facades\Route;
use Molitor\User\Http\Controllers\AuthController;

Route::middleware(['web'])->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'webLogin']);
    Route::post('logout', [AuthController::class, 'webLogout'])->name('logout');
});
