<?php

namespace Molitor\User\Http\Controllers\Api;

use Molitor\Admin\Http\Controllers\Admin\BaseApiController;
use Molitor\User\Http\Requests\UserGroupRequest;
use Molitor\User\Http\Resources\UserGroupFormResource;
use Molitor\User\Http\Resources\UserGroupResource;
use Molitor\User\Models\UserGroup;
use Molitor\User\Repositories\PermissionRepositoryInterface;
use Molitor\User\Repositories\UserGroupPermissionRepositoryInterface;
use Molitor\User\Repositories\UserGroupRepositoryInterface;

class UserGroupApiController extends BaseApiController
{
    public function __construct(
        private UserGroupPermissionRepositoryInterface $userGroupPermissionRepository,
        private UserGroupRepositoryInterface           $userGroupRepository,
        private PermissionRepositoryInterface          $permissionRepository
    )
    {
    }

    public function index()
    {
        return UserGroupResource::collection($this->userGroupRepository->getAll());
    }

    public function show(UserGroup $user_group)
    {
        return new UserGroupResource($user_group);
    }

    public function create()
    {
        return new UserGroupFormResource(new UserGroup());
    }

    public function store(UserGroupRequest $request)
    {
        $userGroup = UserGroup::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        foreach ($this->permissionRepository->getAll() as $permission) {
            $this->userGroupPermissionRepository->set($userGroup, $permission, in_array($permission->id, $request->permission_ids));
        }

        $resorurce = new UserGroupFormResource($userGroup);
        $resorurce->setSuccessMessage('A felhasználói csoport létrejött.');
        $resorurce->setRedirect(route('user.group.index'));
        return $resorurce;
    }

    public function edit(UserGroup $user_group)
    {
        return new UserGroupFormResource($user_group);
    }

    public function update(UserGroup $user_group, UserGroupRequest $request)
    {
        $user_group->name = $request->name;
        $user_group->description = $request->description;
        $user_group->save();

        foreach ($this->permissionRepository->getAll() as $permission) {
            $this->userGroupPermissionRepository->set($user_group, $permission, in_array($permission->id, $request->permission_ids));
        }

        $resorurce = new UserGroupFormResource($user_group);
        $resorurce->setSuccessMessage('A felhasználói csoport el lett mentve.');
        $resorurce->setRedirect(route('user.group.index'));
        return $resorurce;
    }

    public function destroy(UserGroup $user_group)
    {
        $this->userGroupRepository->delete($user_group);

        $resorurce = new UserGroupFormResource();
        $resorurce->setSuccessMessage('A felhasználói csoport törölve lett.');
        $resorurce->setRedirect(route('user.group.index'));
        return $resorurce;
    }
}
