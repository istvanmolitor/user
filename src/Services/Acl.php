<?php

declare(strict_types=1);

namespace Molitor\User\Services;

use Illuminate\Support\Facades\DB;
use Molitor\User\Models\User;

class Acl
{
    private bool $loggedIn = false;
    private int|null $userId = null;
    private array $permissions = [];

    public function __construct()
    {
        $this->init();
    }

    protected function init(): void
    {
        $this->loggedIn = auth()->check();
        if($this->loggedIn) {
            $this->userId = auth()->id();
            $this->loadPermissions();
        }
    }

    protected function loadPermissions(): void
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

        $this->permissions = [];
        foreach (DB::select($sql, [$this->userId]) as $row) {
            $this->permissions[] = $row->name;
        }
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function hasPermission(string|array $permission): bool
    {
        if (!$this->loggedIn) {
            return false;
        }
        elseif (is_string($permission)) {
            $permissionNames = explode(' ', $permission);
        } elseif (is_array($permission)) {
            $permissionNames = $permission;
        } else {
            return false;
        }
        foreach ($permissionNames as $permissionName) {
            if (in_array($permissionName, $this->getPermissions())) {
                return true;
            }
        }
        return false;
    }
}
