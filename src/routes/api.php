<?php

use Illuminate\Support\Facades\Route;
use Molitor\User\Http\Controllers\Admin\PermissionController;
use Molitor\User\Http\Controllers\Admin\UserController;
use Molitor\User\Http\Controllers\Admin\UserGroupController;
use Molitor\User\Http\Controllers\AuthController;

// Authentication routes
Route::prefix('auth')
    ->middleware(['api'])
    ->name('user.auth.')
    ->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('me', [AuthController::class, 'me'])->name('me');
            Route::post('change-password', [AuthController::class, 'changePassword'])->name('change-password');
        });
    });

// Admin routes
Route::prefix('admin/user')
    ->middleware(['api', 'auth:sanctum'])
    ->name('user.admin.')
    ->group(function () {
        // Users
        Route::resource('users', UserController::class);

        // User Groups
        Route::resource('user-groups', UserGroupController::class);

        // Permissions
        Route::resource('permissions', PermissionController::class);
    });
