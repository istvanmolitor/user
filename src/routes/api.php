<?php

use Illuminate\Support\Facades\Route;
use Molitor\User\Http\Controllers\Api\AuthApiController;
use Molitor\User\Http\Controllers\Api\PermissionApiController;
use Molitor\User\Http\Controllers\Api\UserApiController;
use Molitor\User\Http\Controllers\Api\UserGroupApiController;

// Authentication routes
Route::prefix('auth')
    ->middleware(['api'])
    ->name('user.auth.')
    ->group(function () {
        Route::post('login', [AuthApiController::class, 'login'])->name('login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthApiController::class, 'logout'])->name('logout');
            Route::get('me', [AuthApiController::class, 'me'])->name('me');
            Route::post('change-password', [AuthApiController::class, 'changePassword'])->name('change-password');
        });
    });

// Admin routes
Route::prefix('admin/user')
    ->middleware(['api', 'auth:sanctum'])
    ->name('user.')
    ->group(function () {
        // Users
        Route::resource('users', UserApiController::class);

        // User Groups
        Route::resource('user-groups', UserGroupApiController::class);

        // Permissions
        Route::resource('permissions', PermissionApiController::class);
    });
