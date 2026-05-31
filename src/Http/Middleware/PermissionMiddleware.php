<?php

namespace Molitor\User\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission): mixed
    {
        abort_unless(Gate::allows('acl', $permission), Response::HTTP_FORBIDDEN);

        return $next($request);
    }
}