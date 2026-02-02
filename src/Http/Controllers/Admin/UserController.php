<?php
namespace Molitor\User\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Molitor\Admin\Traits\HasAdminFilters;
use Molitor\User\Http\Requests\StoreUserRequest;
use Molitor\User\Http\Requests\UpdateUserRequest;
use Molitor\User\Http\Resources\UserResource;
use Molitor\User\Http\Resources\UserGroupResource;
use Molitor\User\Http\Resources\UserGroupSimpleResource;
use Molitor\User\Models\User;
use Molitor\User\Models\UserGroup;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    use HasAdminFilters;

    #[OA\Get(
        path: "/api/admin/users",
        summary: "List all users",
        tags: ["Users"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(ref: "#/components/schemas/User")
                        ),
                        new OA\Property(
                            property: "meta",
                            type: "object",
                            properties: [
                                new OA\Property(property: "current_page", type: "integer"),
                                new OA\Property(property: "last_page", type: "integer"),
                                new OA\Property(property: "per_page", type: "integer"),
                                new OA\Property(property: "total", type: "integer")
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $query = User::with('userGroups');
        $users = $this->applyAdminFilters($query, $request, ['name', 'email'])
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'data' => UserResource::collection($users->items()),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
            'filters' => $request->only(['search', 'sort', 'direction']),
        ]);
    }

    #[OA\Get(
        path: "/api/admin/users/create",
        summary: "Show form for creating a user",
        tags: ["Users"],
        responses: [
            new OA\Response(response: 200, description: "Success")
        ]
    )]
    public function create(): JsonResponse
    {
        return response()->json([
            'user_groups' => UserGroupSimpleResource::collection(UserGroup::all()),
        ]);
    }

    #[OA\Post(
        path: "/api/admin/users",
        summary: "Store a new user",
        tags: ["Users"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/StoreUserRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Created",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", ref: "#/components/schemas/User"),
                        new OA\Property(property: "message", type: "string")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(StoreUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt(\Illuminate\Support\Str::random(32)),
            'email_verified_at' => $validated['email_verified'] ?? false ? now() : null,
        ]);
        if (isset($validated['user_groups'])) {
            $user->userGroups()->sync($validated['user_groups']);
        }

        $user->load('userGroups');

        return response()->json([
            'data' => new UserResource($user),
            'message' => __('user::user.messages.created'),
        ], 201);
    }

    #[OA\Get(
        path: "/api/admin/users/{user}",
        summary: "Display a specific user",
        tags: ["Users"],
        parameters: [
            new OA\Parameter(name: "user", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", ref: "#/components/schemas/User")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function show(User $user): JsonResponse
    {
        $user->load('userGroups');

        return response()->json([
            'data' => new UserResource($user),
        ]);
    }

    #[OA\Get(
        path: "/api/admin/users/{user}/edit",
        summary: "Show form for editing a user",
        tags: ["Users"],
        parameters: [
            new OA\Parameter(name: "user", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Success"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function edit(User $user): JsonResponse
    {
        $user->load('userGroups');

        return response()->json([
            'data' => new UserResource($user),
            'user_groups' => UserGroupSimpleResource::collection(UserGroup::all()),
        ]);
    }

    #[OA\Put(
        path: "/api/admin/users/{user}",
        summary: "Update a user",
        tags: ["Users"],
        parameters: [
            new OA\Parameter(name: "user", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/UpdateUserRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", ref: "#/components/schemas/User"),
                        new OA\Property(property: "message", type: "string")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'email_verified_at' => $validated['email_verified'] ?? false ? now() : null,
        ]);
        if (isset($validated['user_groups'])) {
            $user->userGroups()->sync($validated['user_groups']);
        }

        $user->load('userGroups');

        return response()->json([
            'data' => new UserResource($user),
            'message' => __('user::user.messages.updated'),
        ]);
    }

    #[OA\Delete(
        path: "/api/admin/users/{user}",
        summary: "Delete a user",
        tags: ["Users"],
        parameters: [
            new OA\Parameter(name: "user", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Success"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'message' => __('user::user.messages.deleted'),
        ]);
    }
}
