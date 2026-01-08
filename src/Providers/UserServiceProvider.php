<?php

namespace Molitor\User\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Molitor\User\Models\User;
use Molitor\User\Repositories\MembershipRepository;
use Molitor\User\Repositories\MembershipRepositoryInterface;
use Molitor\User\Repositories\PermissionRepository;
use Molitor\User\Repositories\PermissionRepositoryInterface;
use Molitor\User\Repositories\UserGroupPermissionRepository;
use Molitor\User\Repositories\UserGroupPermissionRepositoryInterface;
use Molitor\User\Repositories\UserGroupRepository;
use Molitor\User\Repositories\UserGroupRepositoryInterface;
use Molitor\User\Repositories\UserRepository;
use Molitor\User\Repositories\UserRepositoryInterface;
use Molitor\User\Services\Acl;

class UserServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'user');

        Gate::define(
            'acl',
            function ($user, $permission) {
                /** @var Acl $acl */
                $acl = app('acl');
                return $acl->hasPermission($permission);
            }
        );
    }

    public function register()
    {
        $this->app->bind(MembershipRepositoryInterface::class, MembershipRepository::class);
        $this->app->bind(UserGroupPermissionRepositoryInterface::class, UserGroupPermissionRepository::class);
        $this->app->bind(UserGroupRepositoryInterface::class, UserGroupRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);

        $this->app->singleton('acl', function ($app) {
            return new Acl();
        });
    }
}
