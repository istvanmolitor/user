<?php

namespace Molitor\User\Http\Controllers\Admin;

use Molitor\Admin\Http\Controllers\Admin\AdminController;
use Molitor\User\Models\UserGroup;
use Molitor\User\Services\DataTable\UserGroupDataTable;

class UserGroupController extends AdminController
{
    public function index()
    {
        $userGroups = new UserGroupDataTable();

        return view('user::admin.userGroup.index', [
            'userGroups' => $userGroups,
        ]);
    }

    public function indexData()
    {
        return (new UserGroupDataTable())->getJson();
    }

    public function show(UserGroup $user_group)
    {
        return view('user::admin.userGroup.show', [
            'userGroup' => $user_group,
        ]);
    }

    public function create()
    {
        return view('user::admin.userGroup.create');
    }

    public function edit(UserGroup $user_group)
    {
        return view('user::admin.userGroup.edit', [
            'userGroup' => $user_group,
        ]);
    }
}
