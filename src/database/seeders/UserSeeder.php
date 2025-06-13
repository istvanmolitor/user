<?php

namespace Molitor\User\database\seeders;

use Molitor\User\Models\Permission;
use Molitor\User\Models\User;
use Molitor\User\Models\UserGroup;
use Molitor\User\Repositories\MembershipRepository;
use Molitor\User\Repositories\UserGroupPermissionRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = User::create(
            [
                'name' => 'Admin',
                'email' => 'admin@admin.hu',
                'password' => Hash::make('admin'),
            ]
        );

        /*Groups*/

        $adminGroup = UserGroup::create(
            [
                'name' => 'Admin',
                'description' => 'Mindenhez van joga.',
            ]
        );

        $userGroup = UserGroup::create(
            [
                'name' => 'Felhasználó',
                'description' => 'Egyszerű felhasználó.',
            ]
        );

        /*Permissions*/

        $devPermission = Permission::create(
            [
                'name' => 'dev',
                'description' => 'Fejlesztés',
            ]
        );

        $currencyPermission = Permission::create(
            [
                'name' => 'currency',
                'description' => 'Devizák',
            ]
        );

        $languagePermission = Permission::create(
            [
                'name' => 'language',
                'description' => 'Nyelvek',
            ]
        );

        $syncPermission = Permission::create(
            [
                'name' => 'sync',
                'description' => 'Szinkron futtatása',
            ]
        );

        $permissionPermission = Permission::create(
            [
                'name' => 'permission',
                'description' => 'Jogosultságok',
            ]
        );

        $shopPermission = Permission::create(
            [
                'name' => 'shop',
                'description' => 'Áruház adminisztráció',
            ]
        );

        $siteScannerPermission = Permission::create(
            [
                'name' => 'site_scanner',
                'description' => 'Site scanner',
            ]
        );

        $customerPermission = Permission::create(
            [
                'name' => 'customer',
                'description' => 'Ügyfelek',
            ]
        );

        $customerProductPermission = Permission::create(
            [
                'name' => 'customer_product',
                'description' => 'Ügyfelek termékeinek kezelése',
            ]
        );

        $userPermission = Permission::create(
            [
                'name' => 'user',
                'description' => 'Felhasználók',
            ]
        );

        $adminPermission = Permission::create(
            [
                'name' => 'admin',
                'description' => 'Admin',
            ]
        );

        /**/

        $membershipRepository = new MembershipRepository();
        $membershipRepository
            ->set($adminGroup, $adminUser, true);

        $userGroupPermissionRepository = new UserGroupPermissionRepository();
        $userGroupPermissionRepository
            ->set($adminGroup, $syncPermission, true)
            ->set($adminGroup, $permissionPermission, true)
            ->set($adminGroup, $shopPermission, true)
            ->set($adminGroup, $siteScannerPermission, true)
            ->set($adminGroup, $customerPermission, true)
            ->set($adminGroup, $customerProductPermission, true)
            ->set($adminGroup, $userPermission, true)
            ->set($adminGroup, $devPermission, true)
            ->set($adminGroup, $adminPermission, true)
            ->set($adminGroup, $currencyPermission, true)
            ->set($adminGroup, $languagePermission, true)
            ->set($userGroup, $syncPermission, true);
    }
}
