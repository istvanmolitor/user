<?php

declare(strict_types=1);

namespace Molitor\User\Services;

use Molitor\Menu\Services\Menu;
use Molitor\Menu\Services\MenuBuilder;

class UserMenuBuilder extends MenuBuilder
{
    public function init(Menu $menu, string $name, array $params = []): void
    {
        if ($name !== 'admin') {
            return;
        }

        $usersGroup = $menu->addItem(__('user::common.group'), null);
        $usersGroup->setName('users');
        $usersGroup->setIcon('users');

        if (app()->routesAreCached() || count(app('router')->getRoutes()) > 0) {
            $usersGroup->addItem(__('user::user.list'), route('user.admin.users.index'))
                ->setName('users.list')
                ->setIcon('list');

            $usersGroup->addItem(__('user::user-group.list'), route('user.admin.user-groups.index'))
                ->setName('user-groups.list')
                ->setIcon('users-cog');

            $usersGroup->addItem(__('user::permission.list'), route('user.admin.permissions.index'))
                ->setName('permissions.list')
                ->setIcon('shield-alt');
        }
    }
}

