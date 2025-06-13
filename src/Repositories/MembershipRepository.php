<?php

declare(strict_types=1);

namespace Molitor\User\Repositories;

use Molitor\User\Models\User;
use Molitor\User\Models\UserGroup;
use Molitor\User\Models\Membership;

class MembershipRepository implements MembershipRepositoryInterface
{
    private Membership $membership;

    public function __construct()
    {
        $this->membership = new Membership();
    }

    public function exists(UserGroup $userGroup, User $user): bool
    {
        return $this->membership
            ->where('user_group_id', $userGroup->id)
            ->where('user_id', $user->id)
            ->count() > 0;
    }

    public function set(UserGroup $userGroup, User $user, bool $value): self
    {
        if ($this->exists($userGroup, $user) != $value) {
            if ($value) {
                $this->membership->create([
                    'user_group_id' => $userGroup->id,
                    'user_id' => $user->id,
                ]);
            } else {
                $this->membership
                    ->where('user_group_id', $userGroup->id)
                    ->where('user_id', $user->id)
                    ->delete();
            }
        }
        return $this;
    }

    public function getUserGroupIdsByUser(User $user): array
    {
        return $this->membership->where('user_id', $user->id)->pluck('user_group_id')->toArray();
    }

    public function deleteByUser(User $user): void
    {
        $this->membership->where('user_id', $user->id)->delete();
    }

    public function deleteByUserGroup(UserGroup $userGroup): void
    {
        $this->membership->where('user_group_id', $userGroup->id)->delete();
    }
}
