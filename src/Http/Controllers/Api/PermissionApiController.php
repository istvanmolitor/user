<?php

namespace Molitor\User\Http\Controllers\Api;

use Molitor\User\Http\Requests\PermissionRequest;
use Molitor\User\Http\Resources\PermissionFormResource;
use Molitor\User\Http\Resources\PermissionResource;
use Molitor\User\Models\Permission;
use Molitor\User\Repositories\PermissionRepositoryInterface;
use Molitor\User\Repositories\UserGroupPermissionRepositoryInterface;
use Molitor\User\Repositories\UserGroupRepositoryInterface;

class PermissionApiController
{
    public function __construct(
        private UserGroupRepositoryInterface           $userGroupRepository,
        private UserGroupPermissionRepositoryInterface $userGroupPermissionRepository,
        private PermissionRepositoryInterface          $permissionRepository
    )
    {
    }

    public function index()
    {
        return PermissionResource::collection($this->permissionRepository->getAll());
    }

    public function create()
    {
        return new PermissionFormResource(new Permission());
    }

    public function edit(Permission $permission)
    {
        return new PermissionFormResource($permission);
    }

    public function store(PermissionRequest $request)
    {
        $permission = Permission::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        foreach ($this->userGroupRepository->getAll() as $userGroup) {
            $this->userGroupPermissionRepository->set($userGroup, $permission, in_array($userGroup->id, $request->user_group_ids));
        }

        $resource = new PermissionFormResource($permission);
        $resource->setSuccessMessage('A jogosultság létrejött.');
        $resource->setRedirect(route('permission.index'));
        return $resource;
    }

    public function update(Permission $permission, PermissionRequest $request)
    {
        $permission->name = $request->name;
        $permission->description = $request->description;
        $permission->save();

        foreach ($this->userGroupRepository->getAll() as $userGroup) {
            $this->userGroupPermissionRepository->set($userGroup, $permission, in_array($userGroup->id, $request->user_group_ids));
        }

        $resource = new PermissionFormResource($permission);
        $resource->setSuccessMessage('A jogosultság el lett mentve.');
        return $resource;
    }

    public function show(Permission $permission)
    {
        return new PermissionResource($permission);
    }

    public function destroy(Permission $permission)
    {
        $this->permissionRepository->delete($permission);

        $resource = new PermissionFormResource();
        $resource->setSuccessMessage('A jogosultság törölve lett.');
        $resource->setRedirect(route('permission.index'));
        return $resource;
    }
}
