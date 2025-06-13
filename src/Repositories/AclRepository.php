<?php

namespace Molitor\User\Repositories;

use Molitor\User\Models\Permission;
use Molitor\User\Models\UserGroup;

class AclRepository implements AclRepositoryInterface
{
    public function __construct(
        protected UserGroupRepositoryInterface $userGroupRepository,
        protected PermissionRepositoryInterface $permissionRepository,
        protected UserGroupPermissionRepositoryInterface $userGroupPermissionRepository
    ) {
    }

    public function permission(string $name, string $description): Permission
    {
        $permission = $this->permissionRepository->getByName($name);
        if ($permission) {
            return $permission;
        }
        return Permission::create([
            'name' => $name,
            'description' => $description,
        ]);
    }

    public function setUserGroupPermission(string $userGroupName, string|Permission $permissionName): void
    {
        $userGroup = $this->userGroupRepository->getByName($userGroupName);
        if (!$userGroup) {
            $userGroup = UserGroup::create([
                'name' => $userGroupName,
                'description' => $userGroupName,
            ]);
        }

        if ($permissionName instanceof Permission) {
            $permission = $permissionName;
        } else {
            $permission = $this->permissionRepository->getByName($permissionName);
            if (!$permission) {
                $permission = Permission::create([
                    'name' => $permissionName,
                    'description' => $permissionName,
                ]);
            }
        }

        $this->userGroupPermissionRepository->set($userGroup, $permission, true);
    }
}
