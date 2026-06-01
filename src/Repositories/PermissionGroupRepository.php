<?php

declare(strict_types=1);

namespace Molitor\User\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\User\Models\PermissionGroup;

class PermissionGroupRepository implements PermissionGroupRepositoryInterface
{
    private PermissionGroup $permissionGroup;

    public function __construct()
    {
        $this->permissionGroup = new PermissionGroup;
    }

    public function delete(PermissionGroup $permissionGroup): void
    {
        $permissionGroup->delete();
    }

    public function getByName(string $name): ?PermissionGroup
    {
        return $this->permissionGroup->where('name', $name)->first();
    }

    public function create(string $name): PermissionGroup
    {
        $permissionGroup = $this->getByName($name);
        if ($permissionGroup) {
            return $permissionGroup;
        }

        return $this->permissionGroup->create([
            'name' => $name,
        ]);
    }

    public function getAll(): Collection
    {
        return $this->permissionGroup->orderBy('name')->get();
    }

    public function exists(string $name): bool
    {
        return $this->permissionGroup->where('name', $name)->exists();
    }
}