<?php

use Molitor\User\Http\Controllers\Api\PermissionApiController;
use Molitor\User\Http\Controllers\Api\UserApiController;
use Molitor\User\Http\Controllers\Api\UserGroupApiController;

Route::middleware('web')->prefix('/api')->group(
    function () {
        Route::middleware('permission:user')->group(
            function () {
                Route::resource(
                    'user',
                    UserApiController::class,
                    [
                        'names' => 'api.user',
                    ]
                );
            }
        );

        Route::middleware('permission:permission')->group(
            function () {
                Route::resource(
                    'user-group',
                    UserGroupApiController::class,
                    [
                        'names' => 'api.user.group',
                    ]
                );


                Route::resource(
                    'permission',
                    PermissionApiController::class,
                    [
                        'names' => 'api.permission',
                    ]
                );
            }
        );
    }
);
