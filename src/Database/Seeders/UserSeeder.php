<?php

namespace Molitor\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Molitor\User\Exceptions\PermissionException;
use Molitor\User\Exceptions\UserException;
use Molitor\User\Exceptions\UserGroupException;
use Molitor\User\Services\AclManagementService;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aclService = app(AclManagementService::class);

        // Create user groups if they don't exist
        try {
            $aclService->createUserGroup('admin', 'Mindenhez van joga.');
        } catch (UserGroupException $e) {
            // User group already exists, skip
        }

        try {
            $aclService->createUserGroup('user', 'Egyszerű felhasználó', true);
        } catch (UserGroupException $e) {
            // User group already exists, skip
        }

        // Create user if they don't exist
        try {
            $user = $aclService->createUser('admin@example.com', 'admin', 'admin', ['admin', 'user']);
            $user->markEmailAsVerified();
        } catch (UserException $e) {
            // User already exists, skip
        }

        // Create permission if it doesn't exist
        try {
            $aclService->createPermission('permission', 'Jogosultságok', 'admin');
        } catch (PermissionException $e) {
            // Permission already exists, skip
        }
    }
}
