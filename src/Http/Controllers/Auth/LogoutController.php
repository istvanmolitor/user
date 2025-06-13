<?php

namespace Molitor\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Molitor\User\Services\AuthService;

class LogoutController extends Controller
{
    public function __construct(
        private AuthService $authService
    )
    {
    }

    public function logout()
    {
        $this->authService->logout();
        return redirect()->to($this->authService->getAfterLogoutRoute());
    }
}