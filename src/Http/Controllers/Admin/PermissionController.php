<?php

namespace Molitor\User\Http\Controllers\Admin;

use Molitor\Admin\Http\Controllers\Admin\AdminController;
use Molitor\User\Models\Permission;
use Molitor\User\Models\UserGroup;
use Molitor\User\Repositories\UserGroupPermissionRepositoryInterface;
use Molitor\User\Services\DataTable\PermissionDataTable;

class PermissionController extends AdminController
{
    public function __construct(
        private UserGroupPermissionRepositoryInterface $userGroupPermissionRepository
    )
    {
    }

    public function index()
    {
        $permissions = new PermissionDataTable();

        return view('user::admin.permission.index', [
            'permissions' => $permissions,
        ]);
    }

    public function indexData()
    {
        return (new PermissionDataTable())->getJson();
    }

    public function create()
    {
        $userGroups = UserGroup::orderBy('name')->get();

        return view('user::admin.permission.create', [
            'userGroups' => $userGroups,
        ]);
    }

    public function edit(Permission $permission)
    {
        $userGroups = UserGroup::orderBy('name')->get();
        $checked = $permission->userGroups()->pluck('id')->toArray();

        $values = [];
        foreach ($userGroups as $userGroup) {
            $values[$userGroup->id] = in_array($userGroup->id, $checked);
        }

        return view('user::admin.permission.edit', [
            'permission' => $permission,
            'userGroups' => $userGroups,
            'values' => $values,
        ]);
    }
}
