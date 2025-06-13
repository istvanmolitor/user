<?php

namespace Molitor\User\Services\Menu;

use Illuminate\Support\Facades\Gate;
use Molitor\Menu\Services\Menu;
use Molitor\Menu\Services\MenuBuilder;
use Molitor\User\Models\Permission;
use Molitor\User\Models\User;
use Molitor\User\Models\UserGroup;

class UserMenuBuilder extends MenuBuilder
{
    public function admin(Menu $menu)
    {
        if(Gate::allows('acl', 'user')) {
            $menu->addItem('Felhasználók', route('user.index'))->setIcon('user');
        }
    }

    public function userSubNav(Menu $menu)
    {
        $menu->addItem('Felhasználók', route('user.index'))
            ->setName('user')
            ->setIcon('person-fill');

        $menu->addItem('Csoportok', route('user.group.index'))
            ->setName('userGroup')
            ->setIcon('people-fill');

        $menu->addItem('Jogosultságok', route('permission.index'))
            ->setName('permission')
            ->setIcon('key-fill');
    }

    public function userActions(Menu $menu, ?User $user = null)
    {
        $menu->addItem('Lista', route('user.index'))->setIcon('list');
        if ($user) {
            $menu->addItem('Profil', route('user.show', $user))->setIcon('eye');
            $menu->addItem('Szerkesztés', route('user.edit', $user))->setIcon('pencil');
        } else {
            $menu->addItem('Létrehozás', route('user.create'))->setIcon('plus');
        }
    }

    public function userGroupActions(Menu $menu, ?UserGroup $userGroup = null)
    {
        $menu->addItem('Lista', route('user.group.index'))->setIcon('list');
        if ($userGroup) {
            $menu->addItem('Szerkesztés', route('user.group.edit', $userGroup))->setIcon('pencil');
        } else {
            $menu->addItem('Létrehozás', route('user.group.create'))->setIcon('plus');
        }
    }

    public function permissionActions(Menu $menu, ?Permission $permission = null)
    {
        $menu->addItem('Lista', route('permission.index'))->setIcon('list');
        if ($permission) {
            $menu->addItem('Szerkesztés', route('permission.edit', $permission))->setIcon('pencil');
        } else {
            $menu->addItem('Létrehozás', route('permission.create'))->setIcon('plus');
        }
    }
}
