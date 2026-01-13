<?php

use Illuminate\Support\Facades\Route;
use Molitor\User\Http\Controllers\Admin\PermissionController;
use Molitor\User\Http\Controllers\Admin\UserController;
use Molitor\User\Http\Controllers\Admin\UserGroupController;

Route::prefix('admin/user')
    ->middleware(['web', 'auth'])
    ->name('user.admin.')
    ->group(function () {
        // Users
        Route::resource('users', UserController::class);

        // User Groups
        Route::resource('user-groups', UserGroupController::class);

        // Permissions
        Route::resource('permissions', PermissionController::class);
    });

