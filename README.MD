# Laravel Roles

#### Table of contents
- [About](#about)
- [Features](#features)
- [Installation](#installation)
    - [Composer](#composer)
    - [Service Provider](#service-provider)
    - [Publish All Assets](#publish-all-assets)
    - [Publish Specific Assets](#publish-specific-assets)
    - [HasRoleAndPermission Trait And Contract](#hasroleandpermission-trait-and-contract)
    - [Migrations and Seeds](#migrations-and-seeds)
- [Usage](#usage)
    - [Creating Roles](#creating-roles)
    - [Attaching, Detaching and Syncing Roles](#attaching-detaching-and-syncing-roles)
    - [Assign a user role to new registered users](#assign-a-user-role-to-new-registered-users)
    - [Checking For Roles](#checking-for-roles)
    - [Levels](#levels)
    - [Creating Permissions](#creating-permissions)
    - [Attaching, Detaching and Syncing Permissions](#attaching-detaching-and-syncing-permissions)
    - [Checking For Permissions](#checking-for-permissions)
    - [Permissions Inheriting](#permissions-inheriting)
    - [Entity Check](#entity-check)
    - [Blade Extensions](#blade-extensions)
    - [Middleware](#middleware)
- [Configuration](#configuration)
    - [Environment File](#environment-file)
- [More Information](#more-information)
- [Optional API Routes](#optional-api-routes)
- [File Tree](#file-tree)
- [License](#license)

## About
A Powerful package for handling roles and permissions in Laravel.
Supports Laravel 5.8, and 6.0.

## Features
| Laravel Roles Features  |
| :------------ |
|Built in migrations with ability to publish and modify your own.|
|Built in command with ability to modify your own seed data from config.|
|Roles with levels and relationships to users, permissions|
|Permissions with relationships to users and levels|
|Extended role and permission|
|Soft deletes with full restore and destroy|
|Optional CRUD of Roles and Permissions|
|Lots of [configuration](#configuration) options|
|Lots of [configuration](#configuration) seed|
|All Extendable from [.env](#environment-file)

## Installation
This package is very easy to set up. There are only couple of steps.

### Composer
From your projects root folder in terminal run:

Laravel 5.8 and up use:

```
    composer require mistery23/laravel-roles
```

### Service Provider
* Laravel 5.8 and up
Uses package auto discovery feature, no need to edit the `config/app.php` file.

```php
'providers' => [

    ...

    /**
     * Third Party Service Providers...
     */
    Mistery23\LaravelRoles\RolesServiceProvider::class,

],
```

### Publish All Assets
```bash
    php artisan vendor:publish --tag=laravelroles
```

### Publish Specific Assets
```bash
    php artisan vendor:publish --tag=laravelroles-config
    php artisan vendor:publish --tag=laravelroles-migrations
```

### HasRoleAndPermission Trait And Contract

1. Include `HasRoleAndPermission` trait and also implement `HasRoleAndPermission` contract inside your `User` model. See example below.

2. Include `use Mistery23\LaravelRoles\Traits\HasRoleAndPermission;` in the top of your `User` model below the namespace and implement the `HasRoleAndPermission` trait. See example below.

Example `User` model Trait And Contract:

```php

<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Mistery23\LaravelRoles\Traits\HasRoleAndPermission;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoleAndPermission;

    // rest of your model ...
}

```

### Migrations and seeds
> This uses the default users table which is in Laravel. You should already have the migration file for the users table available and migrated.

1. Setup the needed tables:

    `php artisan migrate`

2. Check `config/roles-seed.php`. For create/update your role and perm.
2. Setup role and perm:

    `php artisan roles:gen`

3. Seed an initial set of Permissions, Roles. Attach role/permission to user please implement in your project.
For this you should use complete use cases:

 `Mistery23\LaravelRoles\Model\UseCases\User\Attach\Role`
 `Mistery23\LaravelRoles\Model\UseCases\User\Attach\Permission`

#### Roles Seeded
|Property|Value|
|----|----|
|Name| Root|
|Slug| root|
|Description| Root role|
|Level| 100|

|Property|Value|
|----|----|
|Name| Admin|
|Slug| admin|
|Description| Admin role|
|Level| 90|
|Parent| root|
|Permissions| portal.administrate|

|Property|Value|
|----|----|
|Name| Manager|
|Slug| manager|
|Description| Manager role|
|Level| 70|
|Parent|admin|
|Permissions|portal.user.manage, portal.user.manage.create|

|Property|Value|
|----|----|
|Name| Client|
|Slug| client|
|Description| Client role|
|Level| 50|

#### Permissions Seeded:
|Property|Value|
|----|----|
|name|Portal administrate|
|slug|portal.administrate|
|description|Can administrate portal|

|Property|Value|
|----|----|
|name|Portal manage user|
|slug|portal.user.manage|
|description|Can manage user|
|parent|portal.administrate|

|Property|Value|
|----|----|
|name|Portal user create|
|slug|portal.user.manage.create|
|description|Can user create|
|parent|portal.user.manage.create|

|Property|Value|
|----|----|
|name|Portal client|
|slug|portal.use|
|description|Can use portal|

## Usage

### Creating Roles

```php
$command = new Mistery23\LaravelRoles\Model\UseCases\Role\Create\Command(
    'Admin',
    'admin',
    90,
    'Admin role'
);

app(Mistery23\LaravelRoles\Model\UseCases\Role\Create::class)->handle($command);

```

### Attaching, Detaching Role and Permissions

See `Mistery23\LaravelRoles\Model\UseCases\...` for all actions for role, permission and user.

### Checking For Roles

You can now check if the user has required role.

```php
if ($user->hasRole('admin')) { // you can pass an id or slug
    //
}
```

You can also do this:

```php
if ($user->isAdmin()) {
    //
}
```

And of course, there is a way to check for multiple roles:

```php
if ($user->hasRole(['admin', 'moderator'])) {
    /*
    | Or alternatively:
    | $user->hasRole('admin, moderator'), $user->hasRole('admin|moderator'),
    | $user->hasOneRole('admin, moderator'), $user->hasOneRole(['admin', 'moderator']), $user->hasOneRole('admin|moderator')
    */

    // The user has at least one of the roles
}

if ($user->hasRole(['admin', 'moderator'], true)) {
    /*
    | Or alternatively:
    | $user->hasRole('admin, moderator', true), $user->hasRole('admin|moderator', true),
    | $user->hasAllRoles('admin, moderator'), $user->hasAllRoles(['admin', 'moderator']), $user->hasAllRoles('admin|moderator')
    */

    // The user has all roles
}
```

### Levels

When you are creating roles, there is optional parameter `level`. It is set to `1` by default, but you can overwrite it and then you can do something like this:

```php
if ($user->level() > 4) {
    //
}
```

> If user has multiple roles, method `level` returns the highest one.

`Level` has also big effect on inheriting permissions. About it later.

### Checking For Permissions

```php
if ($user->hasPermission('create.users')) { // you can pass an id or slug
    //
}

if ($user->canDeleteUsers()) {
    //
}
```

You can check for multiple permissions the same way as roles. You can make use of additional methods like `hasOnePermission` or `hasAllPermissions`.

### Permissions Inheriting

Role with higher level is inheriting permission from roles with lower level.

There is an example of this `magic`:

You have three roles: `user`, `moderator` and `admin`. User has a permission to read articles, moderator can manage comments and admin can create articles. User has a level 1, moderator level 2 and admin level 3. It means, moderator and administrator has also permission to read articles, but administrator can manage comments as well.

> If you don't want permissions inheriting feature in you application, simply ignore `level` parameter when you're creating roles.

### Entity Check

Let's say you have an article and you want to edit it. This article belongs to a user (there is a column `user_id` in articles table).

```php
use App\Article;

$article = Article::find(1);

if ($user->allowed('edit.articles', $article)) { // $user->allowedEditArticles($article)
    //
}
```

This condition checks if the current user is the owner of article. If not, it will be looking inside user permissions for a row we created before.

```php
if ($user->allowed('edit.articles', $article, false)) { // now owner check is disabled
    //
}
```

### Blade Extensions

There are four Blade extensions. Basically, it is replacement for classic if statements.

```php
@role('admin') // @if(Auth::check() && Auth::user()->hasRole('admin'))
    // user has admin role
@endrole

@permission('edit.articles') // @if(Auth::check() && Auth::user()->hasPermission('edit.articles'))
    // user has edit articles permissison
@endpermission

@level(2) // @if(Auth::check() && Auth::user()->level() >= 2)
    // user has level 2 or higher
@endlevel

@allowed('edit', $article) // @if(Auth::check() && Auth::user()->allowed('edit', $article))
    // show edit button
@endallowed

@role('admin|moderator', true) // @if(Auth::check() && Auth::user()->hasRole('admin|moderator', true))
    // user has admin and moderator role
@else
    // something else
@endrole
```

### Middleware
This package comes with `VerifyRole`, `VerifyPermission` and `VerifyLevel` middleware.
The middleware aliases are already registered in `\Mistery23\LaravelRoles\RolesServiceProvider`.
You can optionally add them inside your `App/Http/Kernel.php` file with your own aliases like outlined below:

```php
/**
 * The application's route middleware.
 *
 * @var array
 */
protected $routeMiddleware = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
    'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
    'can' => \Illuminate\Auth\Middleware\Authorize::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    'role'          => \Mistery23\LaravelRoles\Middleware\VerifyRole::class,
    'permission'    => \Mistery23\LaravelRoles\Middleware\VerifyPermission::class,
    'level'         => \Mistery23\LaravelRoles\Middleware\VerifyLevel::class,
];
```

Now you can easily protect your routes.

```php
Route::get('/', function () {
    //
})->middleware('role:admin');

Route::get('/', function () {
    //
})->middleware('permission:edit.articles');

Route::get('/', function () {
    //
})->middleware('level:2'); // level >= 2

Route::get('/', function () {
    //
})->middleware('role:admin', 'level:2'); // level >= 2 and Admin

Route::group(['middleware' => ['role:admin']], function () {
    //
});

```

It throws `\Mistery23\LaravelRoles\App\Exceptions\RoleDeniedException`, `\Mistery23\LaravelRoles\App\Exceptions\PermissionDeniedException` or `\Mistery23\LaravelRoles\App\Exceptions\LevelDeniedException` exceptions if it goes wrong.

You can catch these exceptions inside `app/Exceptions/Handler.php` file and do whatever you want.

```php
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        $userLevelCheck = $exception instanceof \Mistery23\LaravelRoles\App\Exceptions\RoleDeniedException ||
            $exception instanceof \Mistery23\LaravelRoles\App\Exceptions\RoleDeniedException ||
            $exception instanceof \Mistery23\LaravelRoles\App\Exceptions\PermissionDeniedException ||
            $exception instanceof \Mistery23\LaravelRoles\App\Exceptions\LevelDeniedException;

        if ($userLevelCheck) {

            if ($request->expectsJson()) {
                return Response::json(array(
                    'error'    =>  403,
                    'message'   =>  'Unauthorized.'
                ), 403);
            }

            abort(403);
        }

        return parent::render($request, $exception);
    }
```

---

## Configuration
* You can change connection for models, models path and there is also a handy pretend feature.
* There are many configurable options which have been extended to be able to configured via `.env` file variables.
* Editing the configuration file directly may not needed because of this.

```php

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
    | you created. Have a look at `Mistery23\LaravelRoles\Model\Entity\Role\Role` model and
    | `Mistery23\LaravelRoles\Model\Entity\Permission\Permission` model.
    |
    */

    'models' => [
        'role'           => env('ROLES_DEFAULT_ROLE_MODEL', Mistery23\LaravelRoles\Model\Entity\Role\Role::class),
        'permission'     => env('ROLES_DEFAULT_PERMISSION_MODEL', Mistery23\LaravelRoles\Model\Entity\Permission\Permission::class),
        'permissionRole' => env('ROLES_DEFAULT_ROLE_PERMISSION_MODEL', Mistery23\LaravelRoles\Model\Entity\RolePermission::class),
        'userRole'       => env('ROLES_DEFAULT_ROLE_USER_MODEL', Mistery23\LaravelRoles\Model\Entity\RoleUser::class),
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
    'rolesApiEnabled'               => env('ROLES_API_ENABLED', true),

    // Enable `auth` middleware
    'rolesAPIAuthEnabled'           => env('ROLES_API_AUTH_ENABLED', true),

    // Enable Roles API middleware
    'rolesAPIMiddlewareEnabled'     => env('ROLES_API_MIDDLEWARE_ENABLED', true),

    // Optional Roles API Middleware
    'rolesAPIMiddleware'            => env('ROLES_API_MIDDLEWARE', 'role:admin'),

];


```

## Configuration seed data
```php
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

```

### Environment File
```
# Roles Default Models
ROLES_DEFAULT_USER_MODEL=App\User
ROLES_DEFAULT_ROLE_MODEL=Mistery23\LaravelRoles\Model\Entity\Role\Role
ROLES_DEFAULT_PERMISSION_MODEL=Mistery23\LaravelRoles\Model\Entity\Permission
ROLES_DEFAULT_ROLE_PERMISSION_MODEL=Mistery23\LaravelRoles\Model\Entity\RolePermission
ROLES_DEFAULT_ROLE_USER_MODEL=Mistery23\LaravelRoles\Model\Entity\RoleUser
ROLES_DEFAULT_PERMISSION_USER_MODEL=Mistery23\LaravelRoles\Model\Entity\PermissionUser

# Roles database information
ROLES_DATABASE_CONNECTION=null
ROLES_USERS_DATABASE_TABLE=user_users
ROLES_ROLES_DATABASE_TABLE=user_roles
ROLES_ROLE_USER_DATABASE_TABLE=user_roles_users
ROLES_PERMISSIONS_DATABASE_TABLE=user_permissions
ROLES_PERMISSION_ROLE_DATABASE_TABLE=user_permissions_roles
ROLES_PERMISSION_USER_DATABASE_TABLE=user_permissions_users

# Dependencies
USER_REPOSITORY=App\Model\User\Repository\UserRepository
USER_QUERIES=App\Model\User\Repository\UserQueries

```

## More Information
For more information, please have a look at [HasRoleAndPermission](https://github.com/jeremykenedy/laravel-roles/blob/master/src/Contracts/HasRoleAndPermission.php) contract.

## Optional API Routes
```
|        | POST     | permissions                                           | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\PermissionsController@store      | auth:api,role:admin                                  |
|        | GET|HEAD | permissions                                           | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\PermissionsController@index      | auth:api,role:admin                                  |
|        | PUT      | permissions/{permissionId}                            | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\PermissionsController@edit       | auth:api,role:admin                                  |
|        | DELETE   | permissions/{permissionId}                            | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\PermissionsController@destroy    | auth:api,role:admin                                  |
|        | PATCH    | permissions/{permissionId}/do-child                   | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\PermissionsController@doChild    | auth:api,role:admin                                  |
|        | PATCH    | permissions/{permissionId}/do-root                    | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\PermissionsController@doRoot     | auth:api,role:admin                                  |
|        | GET|HEAD | roles                                                 | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\RolesController@index            | auth:api,role:admin                                  |
|        | POST     | roles                                                 | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\RolesController@store            | auth:api,role:admin                                  |
|        | PUT      | roles/{roleId}                                        | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\RolesController@edit             | auth:api,role:admin                                  |
|        | DELETE   | roles/{roleId}                                        | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\RolesController@destroy          | auth:api,role:admin                                  |
|        | POST     | roles/{roleId}/copy                                   | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\RolesController@copy             | auth:api,role:admin                                  |
|        | PATCH    | roles/{roleId}/do-child                               | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\RolesController@doChild          | auth:api,role:admin                                  |
|        | PATCH    | roles/{roleId}/do-root                                | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\RolesController@doRoot           | auth:api,role:admin                                  |
|        | DELETE   | roles/{roleId}/permissions                            | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\RolesController@detachPermission | auth:api,role:admin                                  |
|        | POST     | roles/{roleId}/permissions                            | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\RolesController@attachPermission | auth:api,role:admin                                  |
|        | GET|HEAD | roles/{roleId}/permissions                            | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\RolesController@withPermissions  | auth:api,role:admin                                  |
|        | POST     | users/{userId}/permissions                            | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\UsersController@attachPermission | auth:api,role:admin                                  |
|        | DELETE   | users/{userId}/permissions                            | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\UsersController@detachPermission | auth:api,role:admin                                  |
|        | POST     | users/{userId}/roles                                  | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\UsersController@attachRole       | auth:api,role:admin                                  |
|        | DELETE   | users/{userId}/roles                                  | laravelroles::                       | Mistery23\LaravelRoles\App\Http\Controllers\Api\UsersController@detachRole       | auth:api,role:admin                                  |

```

## File Tree
```bash
|-- .env.example
|-- .gitignore
|-- LICENSE.MD
|-- README.MD
|-- composer.json
|-- config
|   |-- roles-seed.php
|   `-- roles.php
|-- database
|   `-- migrations
|       |-- 2016_01_15_105324_create_roles_table.php
|       |-- 2016_01_15_114412_create_role_user_table.php
|       |-- 2016_01_26_115212_create_permissions_table.php
|       |-- 2016_01_26_115523_create_permission_role_table.php
|       `-- 2016_02_09_132439_create_permission_user_table.php
|-- docker
|   `-- development
|       |-- php-cli
|       |   `-- default.ini
|       `-- php-cli.docker
|-- docker-compose.yml
|-- phpunit.xml
|-- resources
|   `-- lang
|       `-- en
|           `-- laravelroles.php
|-- routes
|   `-- api.php
`-- src
    |-- Console
    |   `-- Commands
    |       `-- Roles.php
    |-- Contracts
    |   |-- HasRoleAndPermission.php
    |   |-- PermissionHasRelations.php
    |   |-- RoleHasRelations.php
    |   |-- UserQueriesInterface.php
    |   `-- UserRepositoryInterface.php
    |-- Exceptions
    |   |-- AccessDeniedException.php
    |   |-- LevelDeniedException.php
    |   |-- PermissionDeniedException.php
    |   `-- RoleDeniedException.php
    |-- Http
    |   |-- Controllers
    |   |   `-- Api
    |   |       |-- AbstractController.php
    |   |       |-- PermissionsController.php
    |   |       |-- RolesController.php
    |   |       `-- UsersController.php
    |   |-- Middleware
    |   |   |-- VerifyLevel.php
    |   |   |-- VerifyPermission.php
    |   |   `-- VerifyRole.php
    |   `-- Requests
    |       |-- Permission
    |       |   |-- CreatePermissionRequest.php
    |       |   |-- DoChildPermissionRequest.php
    |       |   `-- UpdatePermissionRequest.php
    |       |-- Role
    |       |   |-- AttachPermissionRequest.php
    |       |   |-- CopyRoleRequest.php
    |       |   |-- CreateRoleRequest.php
    |       |   |-- DetachPermissionRequest.php
    |       |   |-- DoChildRoleRequest.php
    |       |   `-- UpdateRoleRequest.php
    |       `-- User
    |           |-- AttachPermissionRequest.php
    |           |-- AttachRoleRequest.php
    |           |-- DetachPermissionRequest.php
    |           `-- DetachRoleRequest.php
    |-- Model
    |   |-- Entity
    |   |   |-- Permission
    |   |   |   |-- Permission.php
    |   |   |   `-- Repository
    |   |   |       |-- PermissionRepository.php
    |   |   |       `-- PermissionRepositoryInterface.php
    |   |   |-- PermissionUser.php
    |   |   |-- Role
    |   |   |   |-- Repository
    |   |   |   |   |-- RoleRepository.php
    |   |   |   |   `-- RoleRepositoryInterface.php
    |   |   |   `-- Role.php
    |   |   |-- RolePermission.php
    |   |   `-- RoleUser.php
    |   |-- ReadModels
    |   |   |-- PermissionQueries.php
    |   |   |-- PermissionQueriesInterface.php
    |   |   |-- RoleQueries.php
    |   |   `-- RoleQueriesInterface.php
    |   |-- UseCases
    |   |   |-- Permission
    |   |   |   |-- Create
    |   |   |   |   |-- Command.php
    |   |   |   |   `-- Handler.php
    |   |   |   |-- DoChild
    |   |   |   |   |-- Command.php
    |   |   |   |   `-- Handler.php
    |   |   |   |-- DoRoot
    |   |   |   |   |-- Command.php
    |   |   |   |   `-- Handler.php
    |   |   |   |-- Edit
    |   |   |   |   |-- Command.php
    |   |   |   |   `-- Handler.php
    |   |   |   `-- Remove
    |   |   |       |-- Command.php
    |   |   |       `-- Handler.php
    |   |   |-- Role
    |   |   |   |-- Attach
    |   |   |   |   `-- Permission
    |   |   |   |       |-- Command.php
    |   |   |   |       `-- Handler.php
    |   |   |   |-- Copy
    |   |   |   |   |-- Command.php
    |   |   |   |   `-- Handler.php
    |   |   |   |-- Create
    |   |   |   |   |-- Command.php
    |   |   |   |   `-- Handler.php
    |   |   |   |-- Detach
    |   |   |   |   `-- Permission
    |   |   |   |       |-- Command.php
    |   |   |   |       `-- Handler.php
    |   |   |   |-- DoChild
    |   |   |   |   |-- Command.php
    |   |   |   |   `-- Handler.php
    |   |   |   |-- DoRoot
    |   |   |   |   |-- Command.php
    |   |   |   |   `-- Handler.php
    |   |   |   |-- Edit
    |   |   |   |   |-- Command.php
    |   |   |   |   `-- Handler.php
    |   |   |   `-- Remove
    |   |   |       |-- Command.php
    |   |   |       `-- Handler.php
    |   |   `-- User
    |   |       |-- Attach
    |   |       |   |-- Permission
    |   |       |   |   |-- Command.php
    |   |       |   |   `-- Handler.php
    |   |       |   `-- Role
    |   |       |       |-- Command.php
    |   |       |       `-- Handler.php
    |   |       `-- Detach
    |   |           |-- Permission
    |   |           |   |-- Command.php
    |   |           |   `-- Handler.php
    |   |           `-- Role
    |   |               |-- Command.php
    |   |               `-- Handler.php
    |   `-- Utils
    |       |-- Splitter.php
    |       `-- SplitterInterface.php
    |-- RolesFacade.php
    |-- RolesServiceProvider.php
    `-- Traits
        |-- DatabaseTraits.php
        |-- HasRoleAndPermission.php
        |-- PermissionHasRelations.php
        `-- RoleHasRelations.php
```

* Tree command can be installed using brew: `brew install tree`
* File tree generated using command `tree -a -I '.idea|.git|node_modules|vendor|storage|tests|composer.lock'`

## License
This package is free software distributed under the terms of the [MIT license](https://opensource.org/licenses/MIT). Enjoy!
