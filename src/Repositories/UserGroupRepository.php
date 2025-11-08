<?php

declare(strict_types=1);

namespace Molitor\User\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\User\Models\UserGroup;
use Molitor\User\Models\UserGroupPermission;

class UserGroupRepository implements UserGroupRepositoryInterface
{
    private UserGroup $userGroup;

    public function __construct(
        protected MembershipRepositoryInterface $membershipRepository,
        protected UserGroupPermissionRepositoryInterface $userGroupPermissionRepository
    )
    {
        $this->userGroup = new UserGroup();
    }

    public function getAll(): Collection
    {
        return $this->userGroup->orderBy('name')->get();
    }

    public function delete(UserGroup $userGroup)
    {
        $this->membershipRepository->deleteByUserGroup($userGroup);
        $this->userGroupPermissionRepository->deleteByUserGroup($userGroup);
        UserGroupPermission::where('user_group_id', $userGroup->id)->delete();
        $userGroup->delete();
    }

    public function getByName(string $name): ?UserGroup
    {
        return $this->userGroup->where('name', $name)->first();
    }

    public function create(string $name, string $description, bool $isDefault = false): UserGroup
    {
        return $this->userGroup->create([
            'name' => $name,
            'description' => $description,
            'is_default' => $isDefault,
        ]);
    }

    public function getDefaults(): Collection
    {
        return $this->userGroup->where('is_default', true)->get();
    }

    public function exists(string $name): bool
    {
        return $this->userGroup->where('name', $name)->exists();
    }
}
