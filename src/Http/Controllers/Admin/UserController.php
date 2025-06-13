<?php

namespace Molitor\User\Http\Controllers\Admin;

use Molitor\Admin\Http\Controllers\Admin\AdminController;
use Molitor\User\Models\User;
use Molitor\User\Models\UserGroup;
use Molitor\User\Repositories\MembershipRepositoryInterface;
use Molitor\User\Services\DataTable\UserDataTable;
use Inertia\Inertia;

class UserController extends AdminController
{
    public function __construct(
        private MembershipRepositoryInterface $membershipRepository
    )
    {
    }

    public function index()
    {
        return Inertia::render('user/index');
    }

    public function indexData()
    {
        return (new UserDataTable())->getJson();
    }

    public function create()
    {
        $userGroups = UserGroup::orderBy('name')->get();

        return Inertia::render('user/create', [
            'userGroups' => $userGroups,
        ]);
    }

    public function edit(User $user)
    {
        $userGroups = UserGroup::orderBy('name')->get();
        $checked = $user->userGroups()->pluck('id')->toArray();

        $values = [];
        foreach ($userGroups as $userGroup) {
            $values[$userGroup->id] = in_array($userGroup->id, $checked);
        }

        return Inertia::render('user/edit', [
            'user' => $user,
            'userGroups' => $userGroups,
            'values' => $values,
        ]);
    }

    public function show(User $user)
    {
        return Inertia::render('user.show', [
            'user' => $user,
        ]);
    }
}
