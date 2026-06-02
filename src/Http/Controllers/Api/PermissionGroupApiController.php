<?php

namespace Molitor\User\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Molitor\Admin\Traits\HasAdminFilters;
use Molitor\User\Http\Requests\StorePermissionGroupRequest;
use Molitor\User\Http\Requests\UpdatePermissionGroupRequest;
use Molitor\User\Http\Resources\PermissionGroupResource;
use Molitor\User\Models\PermissionGroup;
use Molitor\User\Repositories\PermissionGroupRepositoryInterface;
use OpenApi\Attributes as OA;

class PermissionGroupApiController extends Controller
{
    use HasAdminFilters;

    public function __construct(
        private PermissionGroupRepositoryInterface $permissionGroupRepository
    ) {}

    #[OA\Get(
        path: '/api/admin/permission-groups',
        summary: 'List all permission groups',
        tags: ['Permission Groups'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/PermissionGroup')
                        ),
                        new OA\Property(
                            property: 'meta',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer'),
                                new OA\Property(property: 'last_page', type: 'integer'),
                                new OA\Property(property: 'per_page', type: 'integer'),
                                new OA\Property(property: 'total', type: 'integer'),
                            ]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $query = PermissionGroup::query()->withCount('permissions');

        $permissionGroups = $this->applyAdminFilters($query, $request, ['name'])
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'data' => PermissionGroupResource::collection($permissionGroups->items()),
            'meta' => [
                'current_page' => $permissionGroups->currentPage(),
                'last_page' => $permissionGroups->lastPage(),
                'per_page' => $permissionGroups->perPage(),
                'total' => $permissionGroups->total(),
            ],
            'filters' => $request->only(['search', 'sort', 'direction']),
        ]);
    }

    #[OA\Get(
        path: '/api/admin/permission-groups/create',
        summary: 'Show form for creating a permission group',
        tags: ['Permission Groups'],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
        ]
    )]
    public function create(): JsonResponse
    {
        return response()->json([]);
    }

    #[OA\Post(
        path: '/api/admin/permission-groups',
        summary: 'Store a new permission group',
        tags: ['Permission Groups'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StorePermissionGroupRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/PermissionGroup'),
                        new OA\Property(property: 'message', type: 'string'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StorePermissionGroupRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $permissionGroup = $this->permissionGroupRepository->create($validated['name']);

        return response()->json([
            'data' => new PermissionGroupResource($permissionGroup),
            'message' => __('user::permission-group.messages.created'),
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/permission-groups/{permissionGroup}',
        summary: 'Display a specific permission group',
        tags: ['Permission Groups'],
        parameters: [
            new OA\Parameter(name: 'permissionGroup', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/PermissionGroup'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function show(PermissionGroup $permissionGroup): JsonResponse
    {
        $permissionGroup->loadCount('permissions');

        return response()->json([
            'data' => new PermissionGroupResource($permissionGroup),
        ]);
    }

    #[OA\Get(
        path: '/api/admin/permission-groups/{permissionGroup}/edit',
        summary: 'Show form for editing a permission group',
        tags: ['Permission Groups'],
        parameters: [
            new OA\Parameter(name: 'permissionGroup', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function edit(PermissionGroup $permissionGroup): JsonResponse
    {
        $permissionGroup->loadCount('permissions');

        return response()->json([
            'data' => new PermissionGroupResource($permissionGroup),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/permission-groups/{permissionGroup}',
        summary: 'Update a permission group',
        tags: ['Permission Groups'],
        parameters: [
            new OA\Parameter(name: 'permissionGroup', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdatePermissionGroupRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/PermissionGroup'),
                        new OA\Property(property: 'message', type: 'string'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function update(UpdatePermissionGroupRequest $request, PermissionGroup $permissionGroup): JsonResponse
    {
        $validated = $request->validated();

        $permissionGroup->update([
            'name' => $validated['name'],
        ]);

        $permissionGroup->loadCount('permissions');

        return response()->json([
            'data' => new PermissionGroupResource($permissionGroup),
            'message' => __('user::permission-group.messages.updated'),
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/permission-groups/{permissionGroup}',
        summary: 'Delete a permission group',
        tags: ['Permission Groups'],
        parameters: [
            new OA\Parameter(name: 'permissionGroup', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function destroy(PermissionGroup $permissionGroup): JsonResponse
    {
        if ($permissionGroup->permissions()->exists()) {
            return response()->json([
                'message' => __('user::permission-group.messages.cannot_delete_with_permissions'),
            ], 422);
        }

        $this->permissionGroupRepository->delete($permissionGroup);

        return response()->json([
            'message' => __('user::permission-group.messages.deleted'),
        ]);
    }
}
