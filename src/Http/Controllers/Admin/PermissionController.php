<?php

namespace Molitor\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Molitor\Admin\Controllers\BaseAdminController;
use Molitor\Admin\Traits\HasAdminFilters;
use Molitor\User\Http\Requests\StorePermissionRequest;
use Molitor\User\Http\Requests\UpdatePermissionRequest;
use Molitor\User\Models\Permission;

class PermissionController extends BaseAdminController
{
    use HasAdminFilters;

    public function index(Request $request): Response
    {
        $query = Permission::with('userGroups');
        $permissions = $this->applyAdminFilters($query, $request, ['name', 'description'])
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/User/Permissions/Index', [
            'permissions' => $permissions,
            'filters' => $request->only(['search', 'sort', 'direction']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/User/Permissions/Create');
    }

    public function store(StorePermissionRequest $request)
    {
        $validated = $request->validated();

        Permission::create($validated);

        return redirect()->route('user.admin.permissions.index')
            ->with('success', __('user::permission.messages.created'));
    }

    public function edit(Permission $permission): Response
    {
        $permission->load('userGroups');

        return Inertia::render('Admin/User/Permissions/Edit', [
            'permission' => $permission,
        ]);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $validated = $request->validated();

        $permission->update($validated);

        return redirect()->route('user.admin.permissions.index')
            ->with('success', __('user::permission.messages.updated'));
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('user.admin.permissions.index')
            ->with('success', __('user::permission.messages.deleted'));
    }
}

