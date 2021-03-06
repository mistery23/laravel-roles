<?php

declare(strict_types=1);

namespace Mistery23\LaravelRoles\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mistery23\Flusher\Flusher;
use Mistery23\LaravelRoles\Model\Entity\PermissionUser;
use Mistery23\LaravelRoles\Model\Entity\RoleUser;
use Mistery23\LaravelRoles\Model\ReadModels\PermissionQueriesInterface;
use Mistery23\LaravelRoles\Model\ReadModels\RoleQueriesInterface;
use Mistery23\LaravelRoles\Model\Utils\SplitterInterface;
use Webmozart\Assert\Assert;

/**
 * Trait HasRoleAndPermission
 */
trait HasRoleAndPermission
{
    use Flusher;


    /**
     * User belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('roles.models.role'),
            config('roles.models.userRole')
        )
            ->withTimestamps();
    }

    /**
     * User belongs to many permissions.
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('roles.models.permission'),
            config('roles.models.userPermission')
        )
            ->withTimestamps();
    }

    /**
     * Attach role to a user.
     *
     * @param string $roleId
     *
     * @return void
     */
    public function attachRole($roleId): void
    {
        $flag = $this->roles->contains($roleId);

        Assert::false($flag, 'Role is already attached');

        $this->roles->add(RoleUser::new((string)$this->id, $roleId));
    }

    /**
     * Detach role from a user.
     *
     * @param string $roleId
     *
     * @return void
     */
    public function detachRole($roleId): void
    {
        $flag = $this->roles->contains($roleId);

        Assert::true($flag, 'Role is not attached');

        $this->detachItem('roles', $roleId);
    }

    /**
     * Attach permission to a user.
     *
     * @param string $permissionId
     *
     * @return void
     *
     * @throws \Exception
     */
    public function attachPermission(string $permissionId): void
    {
        $flag = $this->permissions->contains($permissionId);

        Assert::false($flag, 'Permission is already attached');

        $this->permissions->add(PermissionUser::new((string)$this->id, $permissionId));
    }

    /**
     * Detach permission from a user.
     *
     * @param string $permissionId
     *
     * @return void
     */
    public function detachPermission($permissionId): void
    {
        $flag = $this->permissions->contains($permissionId);

        Assert::true($flag, 'Permission is not attached');

        $this->detachItem('permissions', $permissionId);
    }

    /**
     * Check if the user has a role or roles.
     *
     * @param string|array $role
     * @param bool         $all
     *
     * @return boolean
     */
    public function hasRole($role, $all = false): bool
    {
        if (!$all) {
            return $this->hasOneRole($role);
        }

        return $this->hasAllRoles($role);
    }

    /**
     * Check if the user has at least one of the given roles.
     *
     * @param string|array $roles
     *
     * @return boolean
     */
    public function hasOneRole($roles): bool
    {
        foreach ($this->getArrayFrom($roles) as $role) {
            if ($this->checkRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user has all roles.
     *
     * @param string|array $roles
     *
     * @return boolean
     */
    public function hasAllRoles($roles): bool
    {
        foreach ($this->getArrayFrom($roles) as $role) {
            if (!$this->checkRole($role)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the user has role.
     *
     * @param int|string $role
     *
     * @return boolean
     */
    public function checkRole($role): bool
    {
        $queries = app(RoleQueriesInterface::class);

        return $queries->hasRole((string) Auth::user()->getAuthIdentifier(), $role);
    }

    /**
     * Check if the user has a permission or permissions.
     *
     * @param int|string|array $permission
     * @param boolean          $all
     *
     * @return boolean
     */
    public function hasPermission($permission, $all = false): bool
    {
        if (!$all) {
            return $this->hasOnePermission($permission);
        }

        return $this->hasAllPermissions($permission);
    }

    /**
     * Check if the user has at least one of the given permissions.
     *
     * @param string|array $permissions
     *
     * @return boolean
     */
    public function hasOnePermission($permissions): bool
    {
        foreach ($this->getArrayFrom($permissions) as $permission) {
            if ($this->checkPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user has all permissions.
     *
     * @param string|array $permissions
     *
     * @return boolean
     */
    public function hasAllPermissions($permissions): bool
    {
        foreach ($this->getArrayFrom($permissions) as $permission) {
            if (!$this->checkPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the user has a permission.
     *
     * @param string $permission
     *
     * @return boolean
     */
    public function checkPermission($permission): bool
    {
        $queries = app(PermissionQueriesInterface::class);

        $isPresent = $queries->hasUserPermission(
            (string) Auth::user()->getAuthIdentifier(),
            $permission
        );

        if (true === $isPresent) {
            return true;
        }

        $isPresent = $queries->hasRolePermission(
            (string) Auth::user()->getAuthIdentifier(),
            $permission
        );

        return $isPresent;
    }


    /**
     * Get an array from argument.
     *
     * @param integer|string|array $argument
     *
     * @return array
     */
    private function getArrayFrom($argument): array
    {
        return app(SplitterInterface::class)->getArrayFrom($argument);
    }

    /**
     * Get role level of a user.
     *
     * @return integer
     */
    public function level()
    {
        return ($role = $this->roles()->sortByDesc('level')->first()) ? $role->level : 0;
    }

    /**
     * Check if the user is allowed to manipulate with entity.
     *
     * @param string $providedPermission
     * @param Model  $entity
     * @param boolean   $owner
     * @param string $ownerColumn
     *
     * @return boolean
     */
    public function allowed($providedPermission, Model $entity, $owner = true, $ownerColumn = 'user_id'): bool
    {
        if (true === $owner && (string)$entity->{$ownerColumn} === (string)$this->id) {
            return true;
        }

        return $this->isAllowed($providedPermission, $entity);
    }

    /**
     * Check if the user is allowed to manipulate with provided entity.
     *
     * @param string $providedPermission
     * @param Model  $entity
     *
     * @return boolean
     */
    protected function isAllowed($providedPermission, Model $entity): bool
    {
        foreach ($this->permissions as $permission) {
            if (!empty($permission->model) && get_class($entity) === $permission->model
                && ((string)$permission->id === (string)$providedPermission || $permission->slug === $providedPermission)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public function callMagic($method, $parameters)
    {
        if (Str::startsWith($method, 'is')) {
            return $this->hasRole(Str::snake(substr($method, 2), config('roles.separator')));
        } elseif (Str::startsWith($method, 'can')) {
            return $this->hasPermission(Str::snake(substr($method, 3), config('roles.separator')));
        } elseif (Str::startsWith($method, 'allowed')) {
            return $this->allowed(
                Str::snake(substr($method, 7),
                config('roles.separator')),
                $parameters[0],
                (isset($parameters[1])) ? $parameters[1] : true,
                (isset($parameters[2])) ? $parameters[2] : 'user_id'
            );
        }

        return parent::__call($method, $parameters);
    }

    /**
     * @param $method
     * @param $parameters
     *
     * @return boolean
     */
    public function __call($method, $parameters)
    {
        return $this->callMagic($method, $parameters);
    }
}
