<?php

namespace Molitor\User\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Molitor\User\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm(): View
    {
        return view('user::login');
    }

    /**
     * Handle a web login request.
     */
    public function webLogin(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $redirectTo = config('user.redirects.login', '/');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended($redirectTo);
        }

        return back()->withErrors([
            'email' => __('user::auth.failed'),
        ])->onlyInput('email');
    }

    /**
     * Log the user out (Web).
     */
    public function webLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $redirectTo = config('user.redirects.logout', '/');

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect($redirectTo);
    }
}
