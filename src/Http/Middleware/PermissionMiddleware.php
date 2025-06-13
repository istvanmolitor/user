<?php

namespace Molitor\User\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Molitor\User\Services\AuthService;

class PermissionMiddleware
{
    public function __construct(
        private AuthService $authService
    )
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @param $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if(!$this->authService->hasPermission($role)) {
            //return redirect()->to($this->authService->getLoginFormRoute());
        }

        return $next($request);
    }
}
