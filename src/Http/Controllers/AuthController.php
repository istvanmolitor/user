<?php

namespace Molitor\User\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Molitor\User\Http\Requests\LoginRequest;
use Molitor\User\Http\Resources\AuthResource;
use Molitor\User\Http\Resources\UserResource;
use Molitor\User\Models\User;

class AuthController extends Controller
{
    /**
     * Handle a login request to the application.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('user::auth.failed')],
            ]);
        }

        // Revoke all previous tokens
        $user->tokens()->delete();

        // Create new token
        $deviceName = $validated['device_name'] ?? $request->userAgent() ?? 'unknown';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json(
            new AuthResource([
                'user' => $user,
                'token' => $token,
            ])
        );
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // Revoke current token
        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => __('user::auth.logged_out'),
        ]);
    }

    /**
     * Get the authenticated user.
     */
    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load('userGroups');

        return response()->json([
            'data' => new UserResource($user),
        ]);
    }
}
