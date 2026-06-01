<?php

declare(strict_types=1);

namespace Molitor\User\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\User\Models\PermissionGroup;

interface PermissionGroupRepositoryInterface
{
    public function delete(PermissionGroup $permissionGroup): void;

    public function getByName(string $name): ?PermissionGroup;

    public function create(string $name): PermissionGroup;

    public function getAll(): Collection;

    public function exists(string $name): bool;
}