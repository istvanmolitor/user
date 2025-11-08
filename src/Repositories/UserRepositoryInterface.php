<?php

declare(strict_types=1);

namespace Molitor\User\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\User\Models\User;

interface UserRepositoryInterface
{
    public function getByEmail(string $email): ?User;

    public function getAll(): Collection;

    public function delete(User $user): bool;

    public function create(string $name, string $email, string $password): User;
}
