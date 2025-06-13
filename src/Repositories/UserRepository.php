<?php

declare(strict_types=1);

namespace Molitor\User\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\User\Models\User;

class UserRepository implements UserRepositoryInterface
{
    private User $user;

    public function __construct(
        protected MembershipRepositoryInterface $membershipRepository
    )
    {
        $this->user = new User();
    }

    public function getByEmail(string $email): ?User
    {
        return $this->user->where('email', $email)->first();
    }

    public function getAll(): Collection
    {
        return $this->user->orderBy('email')->get();
    }

    public function delete(User $user): bool
    {
        $this->membershipRepository->deleteByUser($user);
        return $user->delete();
    }
}
