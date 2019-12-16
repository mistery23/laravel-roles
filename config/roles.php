<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Package Connection
    |--------------------------------------------------------------------------
    |
    | You can set a different database connection for this package. It will set
    | new connection for models Role and Permission. When this option is null,
    | it will connect to the main database, which is set up in database.php
    |
    */

    'connection'           => env('ROLES_DATABASE_CONNECTION', null),
    'usersTable'           => env('ROLES_USERS_DATABASE_TABLE', 'user_users'),
    'rolesTable'           => env('ROLES_ROLES_DATABASE_TABLE', 'user_roles'),
    'roleUserTable'        => env('ROLES_ROLE_USER_DATABASE_TABLE', 'user_roles_users'),
    'permissionsTable'     => env('ROLES_PERMISSIONS_DATABASE_TABLE', 'user_permissions'),
    'permissionsRoleTable' => env('ROLES_PERMISSION_ROLE_DATABASE_TABLE', 'user_permissions_roles'),
    'permissionsUserTable' => env('ROLES_PERMISSION_USER_DATABASE_TABLE', 'user_permissions_users'),

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | If you want, you can replace default models from this package by models
    | you created. Have a look at `Mistery23\LaravelRoles\Models\Role` model and
    | `Mistery23\LaravelRoles\Models\Permission` model.
    |
    */

    'models' => [
        'role'           => env('ROLES_DEFAULT_ROLE_MODEL', Mistery23\LaravelRoles\Model\Entity\Role\Role::class),
        'permission'     => env('ROLES_DEFAULT_PERMISSION_MODEL', Mistery23\LaravelRoles\Model\Entity\Permission\Permission::class),
        'permissionRole' => env('ROLES_DEFAULT_PERMISSION_MODEL', Mistery23\LaravelRoles\Model\Entity\RolePermission::class),
        'userRole'       => env('ROLES_DEFAULT_PERMISSION_MODEL', Mistery23\LaravelRoles\Model\Entity\RoleUser::class),
        'userPermission' => env('ROLES_DEFAULT_PERMISSION_MODEL', Mistery23\LaravelRoles\Model\Entity\PermissionUser::class),
        'defaultUser'    => env('ROLES_DEFAULT_USER_MODEL', config('auth.providers.users.model')),
    ],

    'dependencies' => [
        'userRepository' => env('USER_REPOSITORY', \App\Model\User\Entity\User\Repository\UserRepository::class),
        'userQueries'    => env('USER_QUERIES', \App\Model\User\Entity\User\Repository\UserQueries::class),
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Roles API Settings
    |--------------------------------------------------------------------------
    |
    | This is the API for Laravel Roles to be able to CRUD them
    | easily and fast via an API. This is optional and is
    | not needed for your application.
    |
    */
    'rolesApiEnabled'               => env('ROLES_API_ENABLED', false),

    // Enable `auth` middleware
    'rolesAPIAuthEnabled'           => env('ROLES_API_AUTH_ENABLED', true),

    // Enable Roles API middleware
    'rolesAPIMiddlewareEnabled'     => env('ROLES_API_MIDDLEWARE_ENABLED', true),

    // Optional Roles API Middleware
    'rolesAPIMiddleware'            => env('ROLES_API_MIDDLEWARE', 'role:admin'),

    // User Permissions or Role needed to create a new role
    'rolesAPICreateNewRolesMiddlewareType'   => env('ROLES_API_CREATE_ROLE_MIDDLEWARE_TYPE', 'role'), //permissions or roles
    'rolesAPICreateNewRolesMiddleware'       => env('ROLES_API_CREATE_ROLE_MIDDLEWARE_TYPE', 'admin'), // admin, XXX. ... or perms.XXX

    // User Permissions or Role needed to create a new permission
    'rolesAPICreateNewPermissionMiddlewareType'  => env('ROLES_API_CREATE_PERMISSION_MIDDLEWARE_TYPE', 'role'), //permissions or roles
    'rolesAPICreateNewPermissionsMiddleware'     => env('ROLES_API_CREATE_PERMISSION_MIDDLEWARE_TYPE', 'admin'), // admin, XXX. ... or perms.XXX

];
