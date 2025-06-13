<?php

namespace Molitor\User\Http\Controllers\Auth;

use Molitor\Admin\Http\Controllers\Admin\BaseApiController;
use Molitor\User\Http\Requests\LoginRequest;
use Molitor\User\Services\AuthService;

class LoginController extends BaseApiController
{
    public function __construct(
        private AuthService $authService
    )
    {
    }

    public function loginForm()
    {
        if ($this->authService->isLoggedIn()) {
            return redirect()->to($this->authService->getAfterLoginRoute());
        }

        return view('user::auth.loginForm');
    }

    public function login(LoginRequest $request)
    {
        if ($this->authService->isLoggedIn()) {
            return [
                'redirect' => $this->authService->getAfterLoginRoute(),
            ];
        }

        $userId = $this->authService->authorize($request->email, $request->password);

        if ($userId !== null) {
            $this->authService->login($userId);

            return [
                'redirect' => $this->authService->getAfterLoginRoute(),
            ];
        } else {
            return response()->json([
                'errors' => [
                    'email' => ['A felhasználó vagy jelszó nem megfelelő']
                ]
            ], 422);
        }
    }
}