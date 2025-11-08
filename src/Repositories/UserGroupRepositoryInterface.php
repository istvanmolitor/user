<?php

declare(strict_types=1);

namespace Molitor\User\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\User\Models\UserGroup;

interface UserGroupRepositoryInterface
{
    public function getAll(): Collection;

    public function delete(UserGroup $userGroup);

    public function getByName(string $name): ?UserGroup;

    public function create(string $name, string $description, bool $isDefault = false): UserGroup;

    public function getDefaults(): Collection;

    public function exists(string $name): bool;
}
