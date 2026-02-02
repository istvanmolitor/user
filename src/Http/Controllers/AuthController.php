<?php
namespace Molitor\User\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Molitor\User\Http\Requests\ChangePasswordRequest;
use Molitor\User\Http\Requests\LoginRequest;
use Molitor\User\Http\Resources\AuthResource;
use Molitor\User\Http\Resources\AuthUserResource;
use Molitor\User\Models\User;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: "/api/user/login",
        summary: "Handle a login request",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/LoginRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(ref: "#/components/schemas/AuthResponse")
            ),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    /**
     * Handle a login request to the application.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = User::with('userGroups.permissions')
            ->where('email', $validated['email'])
            ->first();
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

    #[OA\Post(
        path: "/api/user/logout",
        summary: "Log the user out",
        tags: ["Auth"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(response: 200, description: "Success")
        ]
    )]
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

    #[OA\Get(
        path: "/api/user/me",
        summary: "Get authenticated user",
        tags: ["Auth"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", ref: "#/components/schemas/AuthUser")
                    ]
                )
            )
        ]
    )]
    /**
     * Get the authenticated user with permissions.
     */
    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load('userGroups.permissions');
        return response()->json([
            'data' => new AuthUserResource($user),
        ]);
    }

    #[OA\Post(
        path: "/api/user/change-password",
        summary: "Change user password",
        tags: ["Auth"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/ChangePasswordRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "A jelszó sikeresen megváltozott.")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    /**
     * Change the authenticated user's password.
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validated();

        // Update password
        $user->password = Hash::make($validated['password']);
        $user->save();

        // Optionally revoke all other tokens except current one
        $currentToken = $user->currentAccessToken();
        if ($currentToken) {
            $user->tokens()->where('id', '!=', $currentToken->id)->delete();
        }

        return response()->json([
            'message' => 'A jelszó sikeresen megváltozott.',
        ]);
    }
}
