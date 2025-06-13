<?php

namespace Molitor\User\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Molitor\User\Models\User;
use Molitor\User\Repositories\AclRepository;
use Molitor\User\Repositories\AclRepositoryInterface;
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
use Molitor\User\Services\AuthService;
use Molitor\User\Services\UserAuthService;

class UserServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'user');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'user');

        $this->publishes([
            __DIR__ . '/../resources/js/pages' => resource_path('js/pages/user'),
        ], 'user-pages');

        Gate::define(
            'acl',
            function (User $user, $permission) {
                return app(AuthService::class)->hasPermission($permission);
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
        $this->app->bind(AclRepositoryInterface::class, AclRepository::class);

        $this->app->singleton(UserAuthService::class, function ($app) {
            return new UserAuthService($app->make(UserRepositoryInterface::class));
        });
    }
}
