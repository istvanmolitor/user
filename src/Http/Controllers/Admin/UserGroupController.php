<?php

namespace Molitor\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Molitor\Admin\Controllers\BaseAdminController;
use Molitor\Admin\Traits\HasAdminFilters;
use Molitor\User\Http\Requests\StoreUserGroupRequest;
use Molitor\User\Http\Requests\UpdateUserGroupRequest;
use Molitor\User\Models\Permission;
use Molitor\User\Models\UserGroup;

class UserGroupController extends BaseAdminController
{
    use HasAdminFilters;

    public function index(Request $request): Response
    {
        $query = UserGroup::with('permissions');
        $userGroups = $this->applyAdminFilters($query, $request, ['name', 'description'])
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/UserGroups/Index', [
            'userGroups' => $userGroups,
            'filters' => $request->only(['search', 'sort', 'direction']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/UserGroups/Create', [
            'permissions' => Permission::all(),
        ]);
    }

    public function store(StoreUserGroupRequest $request)
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

        return redirect()->route('user.admin.user-groups.index')
            ->with('success', __('user::user_group.messages.created'));
    }

    public function edit(UserGroup $userGroup): Response
    {
        $userGroup->load('permissions');

        return Inertia::render('Admin/UserGroups/Edit', [
            'userGroup' => $userGroup,
            'permissions' => Permission::all(),
        ]);
    }

    public function update(UpdateUserGroupRequest $request, UserGroup $userGroup)
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

        return back()->with('success', __('user::user_group.messages.updated'));
    }

    public function destroy(UserGroup $userGroup)
    {
        $userGroup->delete();

        return redirect()->route('user.admin.user-groups.index')
            ->with('success', __('user::user_group.messages.deleted'));
    }
}

