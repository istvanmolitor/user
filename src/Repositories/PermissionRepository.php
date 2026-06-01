<?php

declare(strict_types=1);

namespace Molitor\User\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\User\Models\Permission;
use Molitor\User\Models\PermissionGroup;

class PermissionRepository implements PermissionRepositoryInterface
{
    private Permission $permission;

    public function __construct(
        protected UserGroupPermissionRepositoryInterface $userGroupPermissionRepository
    ) {
        $this->permission = new Permission;
    }

    public function delete(Permission $permission): void
    {
        $this->userGroupPermissionRepository->deleteByPermission($permission);
        $permission->delete();
    }

    public function getByName(string $name): ?Permission
    {
        return $this->permission->where('name', $name)->first();
    }

    public function create(PermissionGroup $permissionGroup, string $name, string $description): Permission
    {
        $permission = $this->getByName($name);
        if ($permission) {
            return $permission;
        }

        return $this->permission->create([
            'permission_group_id' => $permissionGroup->id,
            'name' => $name,
            'description' => $description,
        ]);
    }

    public function getAll(): Collection
    {
        return $this->permission->orderBy('name')->get();
    }

    public function exists(string $name): bool
    {
        return $this->permission->where('name', $name)->exists();
    }
}
