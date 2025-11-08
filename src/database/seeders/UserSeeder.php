<?php

namespace Molitor\User\database\seeders;

use Illuminate\Database\Seeder;
use Molitor\User\Services\AclManagementService;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aclService = app(AclManagementService::class);
        $aclService->createUserGroup('admin', 'Mindenhez van joga.');
        $aclService->createUserGroup('user', 'Egyszerű felhasználó', true);

        $aclService->createUser('admin@example.com', 'admin', 'admin', ['admin', 'user']);

        $aclService->createPermission('permission', 'Jogosultságok', 'admin');
    }
}
