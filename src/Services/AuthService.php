<?php

declare(strict_types=1);

namespace Molitor\User\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Molitor\User\Events\UserLoginEvent;
use Molitor\User\Repositories\UserRepositoryInterface;

class AuthService
{
    const SESSION_NAME = 'auth.permissions';
    const REDIRECT_URI_NAME = 'auth.redirect_uri';

    public function __construct(
        private UserRepositoryInterface $userRepository
    )
    {
    }

    public function login(int $userId): void
    {
        Auth::loginUsingId($userId);
        event(new UserLoginEvent($userId));
    }

    public function logout(): void
    {
        Auth::logout();
        $this->setPermissions([]);
    }

    public function getUserId(): ?int
    {
        return Auth::id();
    }

    public function isLoggedIn(): bool
    {
        return Auth::check();
    }

    public function getAfterLoginRoute(): string
    {
        $redirectUri = $this->getRedirectUri();
        if ($redirectUri) {
            return $redirectUri;
        }
        return $this->getAdminRoute();
    }

    public function getAfterLogoutRoute(): string
    {
        return $this->getLoginFormRoute();
    }

    public function getAdminRoute(): string
    {
        return route('admin');
    }

    public function getLoginFormRoute(): string
    {
        return route('user.loginForm');
    }

    public function setPermissions(array $permissions): void
    {
        session()->put(self::SESSION_NAME, $permissions);
    }

    public function getPermissions(): array
    {
        if ($this->isLoggedIn()) {
            $acl = new Acl($this->getUserId());
            return $acl->getPermissions();
        }
        return [];
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->getPermissions());
    }

    public function setRedirectUri(?string $uri): void
    {
        session()->put(self::REDIRECT_URI_NAME, $uri);
    }

    public function getRedirectUri(): ?string
    {
        return session()->get(self::REDIRECT_URI_NAME, null);
    }

    public function authorize(string $email, string $password): ?int
    {
        $user = $this->userRepository->getByEmail($email);
        if ($user) {
            if (Hash::check($password, $user->password)) {
                return $user->id;
            }
        }
        return null;
    }
}