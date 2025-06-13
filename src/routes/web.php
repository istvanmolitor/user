<?php

use Molitor\User\Http\Controllers\Admin\PermissionController;
use Molitor\User\Http\Controllers\Admin\UserController;
use Molitor\User\Http\Controllers\Admin\UserGroupController;
use Molitor\User\Http\Controllers\Auth\LoginController;
use Molitor\User\Http\Controllers\Auth\LogoutController;

Route::middleware('web')->group(function () {

});


Route::middleware('web')->prefix('/admin')->group(
    function () {

        Route::get('login', [LoginController::class, 'loginForm'])->name(
            'user.loginForm'
        );

        Route::post('login', [LoginController::class, 'login'])->name(
            'user.login'
        );

        Route::get('logout', [LogoutController::class, 'logout'])->name(
            'user.logout'
        );

        /* Users */
        Route::resource(
            'user',
            UserController::class,
            [
                'names' => 'user',
            ]
        )->except('store', 'update', 'destroy')
            ->middleware('permission:user');

        Route::post('/user/index-data', [UserController::class, 'indexData'])->name('user.indexData')
            ->middleware('permission:user');

        /* User groups */
        Route::resource(
            'user-group',
            UserGroupController::class,
            [
                'names' => 'user.group',
            ]
        )->except('store', 'update', 'destroy')->middleware('permission:permission');


        Route::post('/user-group/index-data', [UserGroupController::class, 'indexData'])
            ->name('user.group.indexData')
            ->middleware('permission:permission');

        /* Permission */
        Route::resource(
            'permission',
            PermissionController::class,
            [
                'names' => 'permission',
            ]
        )->except('store', 'update', 'show', 'destroy')->middleware('permission:permission');

        Route::post('/permission/index-data', [PermissionController::class, 'indexData'])
            ->name('permission.indexData')
            ->middleware('permission:permission');
    }
);
