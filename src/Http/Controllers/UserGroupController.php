<?php

namespace Molitor\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Molitor\Admin\Traits\HasAdminFilters;
use Molitor\User\Http\Requests\StoreUserGroupRequest;
use Molitor\User\Http\Requests\UpdateUserGroupRequest;
use Molitor\User\Http\Resources\UserGroupResource;
use Molitor\User\Http\Resources\PermissionResource;
use Molitor\User\Http\Resources\PermissionSimpleResource;
use Molitor\User\Models\Permission;
use Molitor\User\Models\UserGroup;
use OpenApi\Attributes as OA;

class UserGroupController extends Controller
{
    use HasAdminFilters;

    #[OA\Get(
        path: "/api/admin/user-groups",
        summary: "List all user groups",
        tags: ["User Groups"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(ref: "#/components/schemas/UserGroup")
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
        $query = UserGroup::with('permissions');
        $userGroups = $this->applyAdminFilters($query, $request, ['name', 'description'])
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'data' => UserGroupResource::collection($userGroups->items()),
            'meta' => [
                'current_page' => $userGroups->currentPage(),
                'last_page' => $userGroups->lastPage(),
                'per_page' => $userGroups->perPage(),
                'total' => $userGroups->total(),
            ],
            'filters' => $request->only(['search', 'sort', 'direction']),
        ]);
    }

    #[OA\Get(
        path: "/api/admin/user-groups/create",
        summary: "Show form for creating a user group",
        tags: ["User Groups"],
        responses: [
            new OA\Response(response: 200, description: "Success")
        ]
    )]
    public function create(): JsonResponse
    {
        return response()->json([
            'permissions' => PermissionSimpleResource::collection(Permission::all()),
        ]);
    }

    #[OA\Post(
        path: "/api/admin/user-groups",
        summary: "Store a new user group",
        tags: ["User Groups"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/StoreUserGroupRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Created",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", ref: "#/components/schemas/UserGroup"),
                        new OA\Property(property: "message", type: "string")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(StoreUserGroupRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $userGroup = UserGroup::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_default' => $validated['is_default'] ?? false,
        ]);

        if (isset($validated['permissions'])) {
            $userGroup->permissions()->sync($validated['permissions']);
        }

        $userGroup->load('permissions');

        return response()->json([
            'data' => new UserGroupResource($userGroup),
            'message' => __('user::user-group.messages.created'),
        ], 201);
    }

    #[OA\Get(
        path: "/api/admin/user-groups/{userGroup}",
        summary: "Display a specific user group",
        tags: ["User Groups"],
        parameters: [
            new OA\Parameter(name: "userGroup", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", ref: "#/components/schemas/UserGroup")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function show(UserGroup $userGroup): JsonResponse
    {
        $userGroup->load('permissions');

        return response()->json([
            'data' => new UserGroupResource($userGroup),
            'permissions' => PermissionSimpleResource::collection(Permission::all()),
        ]);
    }

    #[OA\Get(
        path: "/api/admin/user-groups/{userGroup}/edit",
        summary: "Show form for editing a user group",
        tags: ["User Groups"],
        parameters: [
            new OA\Parameter(name: "userGroup", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Success"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function edit(UserGroup $userGroup): JsonResponse
    {
        $userGroup->load('permissions');

        return response()->json([
            'data' => new UserGroupResource($userGroup),
            'permissions' => PermissionSimpleResource::collection(Permission::all()),
        ]);
    }

    #[OA\Put(
        path: "/api/admin/user-groups/{userGroup}",
        summary: "Update a user group",
        tags: ["User Groups"],
        parameters: [
            new OA\Parameter(name: "userGroup", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/UpdateUserGroupRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", ref: "#/components/schemas/UserGroup"),
                        new OA\Property(property: "message", type: "string")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function update(UpdateUserGroupRequest $request, UserGroup $userGroup): JsonResponse
    {
        $validated = $request->validated();

        $userGroup->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_default' => $validated['is_default'] ?? false,
        ]);

        if (isset($validated['permissions'])) {
            $userGroup->permissions()->sync($validated['permissions']);
        }

        $userGroup->load('permissions');

        return response()->json([
            'data' => new UserGroupResource($userGroup),
            'message' => __('user::user-group.messages.updated'),
        ]);
    }

    #[OA\Delete(
        path: "/api/admin/user-groups/{userGroup}",
        summary: "Delete a user group",
        tags: ["User Groups"],
        parameters: [
            new OA\Parameter(name: "userGroup", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Success"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function destroy(UserGroup $userGroup): JsonResponse
    {
        $userGroup->delete();

        return response()->json([
            'message' => __('user::user-group.messages.deleted'),
        ]);
    }
}

