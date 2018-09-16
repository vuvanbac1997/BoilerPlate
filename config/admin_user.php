<?php

return [
    'roles' => [
        'super_user' => [
            'name'      => 'admin.roles.super_user',
            'sub_roles' => ['admin'],
        ],
        'admin'      => [
            'name'      => 'admin.roles.admin',
            'sub_roles' => [],
        ]
    ],
];
