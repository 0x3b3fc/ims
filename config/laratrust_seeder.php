<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'admin' => [
            'users' => 'l,c,r,u,d',
        ],
        'manager' => [
            'users' => 'l,c,r,u',
        ],
        'viewer' => [
            'users' => 'l,r',
        ],
    ],

    'permissions_map' => [
        'l' => 'list',
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
