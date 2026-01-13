<?php

namespace Molitor\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Molitor\User\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request): Response
    {
        $permissions = Permission::with('userGroups')
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('User/Admin/Permissions/Index', [
            'permissions' => $permissions,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('User/Admin/Permissions/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'description' => 'nullable|string',
        ]);

        Permission::create($validated);

        return redirect()->route('user.admin.permissions.index')
            ->with('success', __('user::permission.messages.created'));
    }

    public function edit(Permission $permission): Response
    {
        $permission->load('userGroups');

        return Inertia::render('User/Admin/Permissions/Edit', [
            'permission' => $permission,
        ]);
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string',
        ]);

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

