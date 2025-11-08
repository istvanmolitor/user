<?php

declare(strict_types=1);

namespace Molitor\User\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\User\Models\Permission;

interface PermissionRepositoryInterface
{
    public function delete(Permission $permission): void;

    public function getByName(string $name): Permission|null;

    public function create(string $name, string $description): Permission;

    public function getAll(): Collection;

    public function exists(string $name): bool;
}
