<?php

namespace Molitor\User\Services;

use Molitor\User\Exceptions\PermissionException;
use Molitor\User\Exceptions\UserException;
use Molitor\User\Exceptions\UserGroupException;
use Molitor\User\Models\Permission;
use Molitor\User\Models\User;
use Molitor\User\Models\UserGroup;
use Molitor\User\Repositories\MembershipRepositoryInterface;
use Molitor\User\Repositories\PermissionRepositoryInterface;
use Molitor\User\Repositories\UserGroupPermissionRepositoryInterface;
use Molitor\User\Repositories\UserGroupRepositoryInterface;
use Molitor\User\Repositories\UserRepositoryInterface;

class AclManagementService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected UserGroupRepositoryInterface $userGroupRepository,
        protected PermissionRepositoryInterface $permissionRepository,
        protected MembershipRepositoryInterface $membershipRepository,
        protected UserGroupPermissionRepositoryInterface $userGroupPermissionRepository
    ) {
    }

    /**
     * @throws PermissionException
     */
    public function getPermission(string $name): Permission
    {
        $permission = $this->permissionRepository->getByName($name);
        if (!$permission) {
            throw new PermissionException($name);
        }
        return $permission;
    }

    public function getUserGroup(string $name): UserGroup
    {
        $userGroup = $this->userGroupRepository->getByName($name);
        if (!$userGroup) {
            throw new UserGroupException($name);
        }
        return $userGroup;
    }

    public function setUserGroupPermission(string|UserGroup $userGroup, string|Permission $permission): self
    {
        if (!($userGroup instanceof UserGroup)) {
            $userGroup = $this->userGroupRepository->getByName($userGroup);
        }
        if (!$userGroup) {
            $userGroup = UserGroup::create([
                'name' => $userGroup,
                'description' => $userGroup,
            ]);
        }

        if (!($permission instanceof Permission)) {
            $permission = $this->permissionRepository->getByName($permission);
            if (!$permission) {
                $permission = Permission::create([
                    'name' => $permission,
                    'description' => $permission,
                ]);
            }
        }

        $this->userGroupPermissionRepository->set($userGroup, $permission, true);
        return $this;
    }

    public function setDefaultUserGroups(User $user): self
    {
        foreach ($this->userGroupRepository->getDefaults() as $userGroup) {
            $this->membershipRepository->set($userGroup, $user, true);
        }
        return $this;
    }

    public function createUser(string $email, string $name, string $password, array $userGroups = null): User
    {
        $user = $this->userRepository->getByEmail($email);
        if($user) {
            throw new UserException('User already exists: ' . $email);
        }

        $user = $this->userRepository->create($name, $email, $password);
        if($userGroups) {
            foreach ($userGroups as $userGroup) {
                $this->membershipRepository->set($this->getUserGroup($userGroup), $user, true);
            }
        }
        else {
            $this->setDefaultUserGroups($user);
        }
        return $user;
    }

    public function createPermission(string $name, string $description, array|string $userGroups): Permission
    {
        if(is_string($userGroups)) {
            $userGroups = [$userGroups];
        }

        if($this->permissionRepository->exists($name)) {
            throw new PermissionException('Permission already exists: ' . $name);
        }

        $permission = $this->permissionRepository->create($name, $description);
        foreach ($userGroups as $userGroup) {
            $this->userGroupPermissionRepository->set($this->getUserGroup($userGroup), $permission, true);
        }
        return $permission;
    }

    public function createUserGroup(string $name, string $description, bool $isDefault = false): UserGroup
    {
        if($this->userGroupRepository->exists($name)) {
            throw new UserGroupException('User group already exists: ' . $name);
        }
        return $this->userGroupRepository->create($name, $description, $isDefault);
    }
}
