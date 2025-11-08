<?php

declare(strict_types=1);

namespace Molitor\User\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
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

    public function create(string $name, string $email, string $password): User
    {
        return $this->user->create(
            [
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]
        );
    }
}
