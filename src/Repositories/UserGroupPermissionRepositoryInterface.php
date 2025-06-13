<?php

declare(strict_types=1);

namespace Molitor\User\Repositories;

use Molitor\User\Models\Permission;
use Molitor\User\Models\UserGroup;

interface UserGroupPermissionRepositoryInterface
{
    public function exists(UserGroup $userGroup, Permission $permission): bool;

    public function set(UserGroup $userGroup, Permission $permission, bool $value): self;

    public function getUserGroupIdsByPermission(Permission $permission): array;

    public function getPermissionIdsByUserGroup(UserGroup $userGroup): array;

    public function deleteByPermission(Permission $permission): void;

    public function deleteByUserGroup(UserGroup $userGroup): void;
}
