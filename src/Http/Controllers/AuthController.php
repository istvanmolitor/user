<?php

namespace Molitor\User\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Molitor\User\Http\Requests\ForgotPasswordRequest;
use Molitor\User\Http\Requests\LoginRequest;
use Molitor\User\Http\Requests\RegisterRequest;
use Molitor\User\Http\Requests\ResetPasswordRequest;
use Molitor\User\Models\User;

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
     * Show the registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('user::register');
    }

    /**
     * Handle a registration request.
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        $redirectTo = config('user.redirects.login', '/');

        return redirect($redirectTo);
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

    /**
     * Show the forgot password form.
     */
    public function showForgotPasswordForm(): View
    {
        return view('user::forgot-password');
    }

    /**
     * Handle the forgot password request.
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show the reset password form.
     */
    public function showResetPasswordForm(Request $request, ?string $token = null): View
    {
        return view('user::reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle the reset password request.
     */
    public function resetPassword(ResetPasswordRequest $request): RedirectResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                Auth::login($user);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
