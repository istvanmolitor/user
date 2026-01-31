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

class PermissionController extends Controller
{
    use HasAdminFilters;

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

    public function create(): JsonResponse
    {
        return response()->json([
            'user_groups' => UserGroupSimpleResource::collection(UserGroup::all()),
        ]);
    }

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

    public function show(Permission $permission): JsonResponse
    {
        $permission->load('userGroups');

        return response()->json([
            'data' => new PermissionResource($permission),
        ]);
    }

    public function edit(Permission $permission): JsonResponse
    {
        $permission->load('userGroups');

        return response()->json([
            'data' => new PermissionResource($permission),
            'user_groups' => UserGroupSimpleResource::collection(UserGroup::all()),
        ]);
    }

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

    public function destroy(Permission $permission): JsonResponse
    {
        $permission->delete();

        return response()->json([
            'message' => __('user::permission.messages.deleted'),
        ]);
    }
}

