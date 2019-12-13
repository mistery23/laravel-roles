<?php

return [
    'users' => [
        'root' => [
            'email'    => 'root@root.root',
            'password' => 'secret',
            'roles'    => ['root'],
        ],
        'admin' => [
            'email'    => 'admin@admin.admin',
            'password' => 'secret',
            'roles'    => ['admin'],
        ],
        'manager' => [
            'email'    => 'manager@manager.manager',
            'password' => 'secret',
            'roles'    => ['manager'],
        ],
        'client' => [
            'email'    => 'client@client.client',
            'password' => 'secret',
            'roles'    => ['client'],
        ],
    ],

    'roles' => [
        'root' => [
            'name'        => 'Root',
            'description' => 'Root role',
            'level'       => 100,
            'parent'      => null,
            'permissions' => [],
        ],
        'admin' => [
            'name'        => 'Admin',
            'description' => 'Admin role',
            'level'       => 90,
            'parent'      => 'root',
            'permissions' => ['portal.administrate'],
        ],
        'manager' => [
            'name'        => 'Manager',
            'description' => 'Manager role',
            'level'       => 70,
            'parent'      => 'admin',
            'permissions' => ['portal.user.manage', 'portal.user.manage.create'],
        ],
        'client' => [
            'name'        => 'Client',
            'description' => 'Client role',
            'level'       => 50,
            'parent'      => null,
            'permissions' => ['portal.use'],
        ],
    ],

    'permissions' => [
        'portal.administrate' => [
            'name'        => 'Portal administrate',
            'description' => 'Can administrate portal',
            'model'       => null,
            'parent'      => null,
        ],
        'portal.user.manage' => [
            'name'        => 'Portal manage user',
            'description' => 'Can manage user',
            'model'       => null,
            'parent'      => 'portal.administrate'
        ],
        'portal.user.manage.create' => [
            'name'        => 'Portal user create',
            'description' => 'Can user create',
            'model'       => null,
            'parent'      => 'portal.user.manage.create'
        ],
        'portal.use' => [
            'name'        => 'Portal client',
            'description' => 'Can use portal',
            'model'       => null,
            'parent'      => null
        ]
    ],
];
