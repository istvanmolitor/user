<?php

declare(strict_types=1);

namespace Molitor\User\Services;

use Illuminate\Support\Facades\DB;
use Molitor\User\Models\User;

class Acl
{
    private ?int $userId;
    private static array $permissions = [];

    public function __construct(User|int $user)
    {
        if (is_int($user)) {
            $this->userId = $user;
        } elseif ($user instanceof User) {
            $this->userId = $user->id;
        }
        if ($this->userId !== null && !array_key_exists($this->userId, self::$permissions)) {
            $this->loadPermissions();
        }
    }

    protected function loadPermissions()
    {
        $sql = "
                SELECT p.name AS name
                FROM users u
                INNER JOIN memberships m ON m.user_id = u.id
                INNER JOIN user_groups ug ON ug.id = m.user_group_id
                INNER JOIN user_group_permissions ugp ON ugp.user_group_id = ug.id
                INNER JOIN permissions p ON ugp.permission_id = p.id
                WHERE u.id = ?
                GROUP BY p.name
            ";
        self::$permissions[$this->userId] = [];
        foreach (DB::select($sql, [$this->userId]) as $row) {
            self::$permissions[$this->userId][] = $row->name;
        }
    }

    public function getPermissions(): array
    {
        return self::$permissions[$this->userId];
    }

    public function hasPermission($permission): bool
    {
        if (is_string($permission)) {
            $permissionNames = explode(' ', $permission);
        } elseif (is_array($permission)) {
            $permissionNames = $permission;
        } else {
            return false;
        }

        foreach ($permissionNames as $permissionName) {
            if (in_array($permissionName, self::$permissions[$this->userId])) {
                return true;
            }
        }
        return false;
    }
}
