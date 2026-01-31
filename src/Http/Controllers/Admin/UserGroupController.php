<?php

namespace Molitor\User\Http\Controllers\Admin;

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

class UserGroupController extends Controller
{
    use HasAdminFilters;

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

    public function create(): JsonResponse
    {
        return response()->json([
            'permissions' => PermissionSimpleResource::collection(Permission::all()),
        ]);
    }

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

    public function show(UserGroup $userGroup): JsonResponse
    {
        $userGroup->load('permissions');

        return response()->json([
            'data' => new UserGroupResource($userGroup),
            'permissions' => PermissionSimpleResource::collection(Permission::all()),
        ]);
    }

    public function edit(UserGroup $userGroup): JsonResponse
    {
        $userGroup->load('permissions');

        return response()->json([
            'data' => new UserGroupResource($userGroup),
            'permissions' => PermissionSimpleResource::collection(Permission::all()),
        ]);
    }

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

    public function destroy(UserGroup $userGroup): JsonResponse
    {
        $userGroup->delete();

        return response()->json([
            'message' => __('user::user-group.messages.deleted'),
        ]);
    }
}

