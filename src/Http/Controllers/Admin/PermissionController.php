<?php

namespace Molitor\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Molitor\Admin\Traits\HasAdminFilters;
use Molitor\User\Http\Requests\StorePermissionRequest;
use Molitor\User\Http\Requests\UpdatePermissionRequest;
use Molitor\User\Http\Resources\PermissionResource;
use Molitor\User\Http\Resources\UserGroupSimpleResource;
use Molitor\User\Models\Permission;
use Molitor\User\Models\UserGroup;
use OpenApi\Attributes as OA;

class PermissionController extends Controller
{
    use HasAdminFilters;

    #[OA\Get(
        path: "/api/admin/permissions",
        summary: "List all permissions",
        tags: ["Permissions"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(ref: "#/components/schemas/Permission")
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
        $query = Permission::with('userGroups');
        $permissions = $this->applyAdminFilters($query, $request, ['name', 'description'])
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'data' => PermissionResource::collection($permissions->items()),
            'meta' => [
                'current_page' => $permissions->currentPage(),
                'last_page' => $permissions->lastPage(),
                'per_page' => $permissions->perPage(),
                'total' => $permissions->total(),
            ],
            'filters' => $request->only(['search', 'sort', 'direction']),
        ]);
    }

    #[OA\Get(
        path: "/api/admin/permissions/create",
        summary: "Show form for creating a permission",
        tags: ["Permissions"],
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
        path: "/api/admin/permissions",
        summary: "Store a new permission",
        tags: ["Permissions"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/StorePermissionRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Created",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", ref: "#/components/schemas/Permission"),
                        new OA\Property(property: "message", type: "string")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(StorePermissionRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $permission = Permission::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        if (isset($validated['user_groups'])) {
            $permission->userGroups()->sync($validated['user_groups']);
        }

        return response()->json([
            'data' => new PermissionResource($permission),
            'message' => __('user::permission.messages.created'),
        ], 201);
    }

    #[OA\Get(
        path: "/api/admin/permissions/{permission}",
        summary: "Display a specific permission",
        tags: ["Permissions"],
        parameters: [
            new OA\Parameter(name: "permission", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", ref: "#/components/schemas/Permission")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function show(Permission $permission): JsonResponse
    {
        $permission->load('userGroups');

        return response()->json([
            'data' => new PermissionResource($permission),
        ]);
    }

    #[OA\Get(
        path: "/api/admin/permissions/{permission}/edit",
        summary: "Show form for editing a permission",
        tags: ["Permissions"],
        parameters: [
            new OA\Parameter(name: "permission", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Success"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function edit(Permission $permission): JsonResponse
    {
        $permission->load('userGroups');

        return response()->json([
            'data' => new PermissionResource($permission),
            'user_groups' => UserGroupSimpleResource::collection(UserGroup::all()),
        ]);
    }

    #[OA\Put(
        path: "/api/admin/permissions/{permission}",
        summary: "Update a permission",
        tags: ["Permissions"],
        parameters: [
            new OA\Parameter(name: "permission", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/UpdatePermissionRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", ref: "#/components/schemas/Permission"),
                        new OA\Property(property: "message", type: "string")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse
    {
        $validated = $request->validated();

        $permission->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        if (isset($validated['user_groups'])) {
            $permission->userGroups()->sync($validated['user_groups']);
        }

        $permission->load('userGroups');

        return response()->json([
            'data' => new PermissionResource($permission),
            'message' => __('user::permission.messages.updated'),
        ]);
    }

    #[OA\Delete(
        path: "/api/admin/permissions/{permission}",
        summary: "Delete a permission",
        tags: ["Permissions"],
        parameters: [
            new OA\Parameter(name: "permission", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Success"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function destroy(Permission $permission): JsonResponse
    {
        $permission->delete();

        return response()->json([
            'message' => __('user::permission.messages.deleted'),
        ]);
    }
}

