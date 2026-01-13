<?php

return [
    'admin' => [
        'title' => 'User Admin',
        'icon' => 'users',
        'items' => [
            [
                'title' => 'Users',
                'url' => 'user.admin.users.index',
                'icon' => 'user',
            ],
            [
                'title' => 'User Groups',
                'url' => 'user.admin.user-groups.index',
                'icon' => 'users',
            ],
            [
                'title' => 'Permissions',
                'url' => 'user.admin.permissions.index',
                'icon' => 'shield',
            ],
        ],
    ],
];

