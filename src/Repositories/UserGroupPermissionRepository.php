<?php

declare(strict_types=1);

namespace Molitor\User\Repositories;

use Molitor\User\Models\Permission;
use Molitor\User\Models\UserGroup;
use Molitor\User\Models\UserGroupPermission;

class UserGroupPermissionRepository implements UserGroupPermissionRepositoryInterface
{
    private UserGroupPermission $userGroupPermission;

    public function __construct()
    {
        $this->userGroupPermission = new UserGroupPermission();
    }

    public function exists(UserGroup $userGroup, Permission $permission): bool
    {
        return $this->userGroupPermission
                ->where('user_group_id', $userGroup->id)
                ->where('permission_id', $permission->id)
                ->count() > 0;
    }

    public function set(UserGroup $userGroup, Permission $permission, bool $value): self
    {
        if ($this->exists($userGroup, $permission) != $value) {
            if ($value) {
                $this->userGroupPermission->create([
                    'user_group_id' => $userGroup->id,
                    'permission_id' => $permission->id,
                ]);
            } else {
                $this->userGroupPermission
                    ->where('user_group_id', $userGroup->id)
                    ->where('permission_id', $permission->id)
                    ->delete();
            }
        }
        return $this;
    }

    public function getUserGroupIdsByPermission(Permission $permission): array
    {
        return $this->userGroupPermission->where('permission_id', $permission->id)->pluck('user_group_id')->toArray();
    }

    public function getPermissionIdsByUserGroup(UserGroup $userGroup): array
    {
        return $this->userGroupPermission->where('user_group_id', $userGroup->id)->pluck('permission_id')->toArray();
    }

    public function deleteByPermission(Permission $permission): void
    {
        $this->userGroupPermission->where('permission_id', $permission->id)->delete();
    }

    public function deleteByUserGroup(UserGroup $userGroup): void
    {
        $this->userGroupPermission->where('user_group_id', $userGroup->id)->delete();
    }
}
