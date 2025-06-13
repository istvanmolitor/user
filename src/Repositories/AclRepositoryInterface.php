<?php

namespace Molitor\User\Repositories;

use Molitor\User\Models\Permission;

interface AclRepositoryInterface
{
    public function permission(string $name, string $description): Permission;

    public function setUserGroupPermission(string $userGroupName, string|Permission $permissionName): void;
}