<?php

declare(strict_types=1);

namespace Molitor\User\Repositories;

use Molitor\User\Models\User;
use Molitor\User\Models\UserGroup;

interface MembershipRepositoryInterface
{
    public function exists(UserGroup $userGroup, User $user): bool;

    public function set(UserGroup $userGroup, User $user, bool $value): self;

    public function getUserGroupIdsByUser(User $user): array;

    public function deleteByUser(User $user): void;

    public function deleteByUserGroup(UserGroup $userGroup): void;
}
