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
            'categories' => 'l,c,r,u,d',
            'products' => 'l,c,r,u,d',
            'orders' => 'l,c,r,u,d',
            'stock_history' => 'l,c,r,u,d',
        ],
        'manager' => [
            'users' => 'l,c,r,u',
            'categories' => 'l,c,r,u',
            'products' => 'l,c,r,u',
            'orders' => 'l,r',
            'stock_history' => 'l,r',
        ],
        'viewer' => [
            'users' => 'l,r',
            'categories' => 'l,r',
            'products' => 'l,r',
            'orders' => 'l,r',
            'stock_history' => 'l,r',
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
