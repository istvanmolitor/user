<?php

namespace Molitor\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Molitor\Admin\Controllers\BaseAdminController;
use Molitor\User\Models\Permission;
use Molitor\User\Models\UserGroup;

class UserGroupController extends BaseAdminController
{
    public function index(Request $request): Response
    {
        $userGroups = UserGroup::with('permissions')
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('User/Admin/UserGroups/Index', [
            'userGroups' => $userGroups,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('User/Admin/UserGroups/Create', [
            'permissions' => Permission::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:user_groups,name',
            'description' => 'nullable|string',
            'is_default' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

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

        return Inertia::render('User/Admin/UserGroups/Edit', [
            'userGroup' => $userGroup,
            'permissions' => Permission::all(),
        ]);
    }

    public function update(Request $request, UserGroup $userGroup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:user_groups,name,' . $userGroup->id,
            'description' => 'nullable|string',
            'is_default' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

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

