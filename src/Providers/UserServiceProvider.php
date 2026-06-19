<?php

namespace Molitor\User\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Molitor\User\Http\Middleware\PermissionMiddleware;
use Molitor\User\Repositories\MembershipRepository;
use Molitor\User\Repositories\MembershipRepositoryInterface;
use Molitor\User\Repositories\PermissionRepository;
use Molitor\User\Repositories\PermissionRepositoryInterface;
use Molitor\User\Repositories\PermissionGroupRepository;
use Molitor\User\Repositories\PermissionGroupRepositoryInterface;
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
        $this->app->make(Router::class)->aliasMiddleware('permission', PermissionMiddleware::class);

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'user');
        $this->publishes([
            __DIR__.'/../../config/user.php' => config_path('user.php'),
        ], 'user-config');

        // Load API routes with /api prefix
        $this->app->make(Router::class)
            ->group(['prefix' => 'api'], __DIR__.'/../routes/api.php');

        $this->publishes([
            __DIR__.'/../../resources/js/pages/Admin' => resource_path('js/pages/Admin/User'),
        ], 'admin-pages');

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
        $this->mergeConfigFrom(__DIR__.'/../../config/user.php', 'user');

        $this->app->bind(MembershipRepositoryInterface::class, MembershipRepository::class);
        $this->app->bind(UserGroupPermissionRepositoryInterface::class, UserGroupPermissionRepository::class);
        $this->app->bind(UserGroupRepositoryInterface::class, UserGroupRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(PermissionGroupRepositoryInterface::class, PermissionGroupRepository::class);

        $this->app->singleton('acl', function ($app) {
            return new Acl;
        });
    }
}
