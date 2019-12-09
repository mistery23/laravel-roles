<?php

namespace jeremykenedy\LaravelRoles\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use jeremykenedy\LaravelRoles\Model\Entity\PermissionUser;
use jeremykenedy\LaravelRoles\Model\Entity\RoleUser;
use jeremykenedy\LaravelRoles\Model\Utils\SplitterInterface;
use Mistery23\EloquentSmartPushRelations\SmartPushRelations;
use Webmozart\Assert\Assert;

trait HasRoleAndPermission
{
    use SmartPushRelations;


    /**
     * User belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles()
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
    public function permissions()
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

        $this->roles->add(RoleUser::new($this->id, $roleId));
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
     */
    public function attachPermission($permissionId)
    {
        $flag = $this->permissions->contains($permissionId);

        Assert::false($flag, 'Permission is already attached');

        $this->permissions->add(PermissionUser::new($this->id, $permissionId));
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
    public function hasRole($role, $all = false)
    {
        if (!$all) {
            return $this->hasOneRole($role);
        }

        return $this->hasAllRoles($role);
    }

    /**
     * Check if the user has at least one of the given roles.
     *
     * @param int|string|array $role
     *
     * @return bool
     */
    public function hasOneRole($role)
    {
        foreach ($this->getArrayFrom($role) as $role) {
            if ($this->checkRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user has all roles.
     *
     * @param int|string|array $role
     *
     * @return bool
     */
    public function hasAllRoles($role)
    {
        foreach ($this->getArrayFrom($role) as $role) {
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
    public function checkRole($role)
    {
        return $this->roles->contains(function ($value) use ($role) {
            return $role == $value->id || Str::is($role, $value->slug);
        });
    }

    /**
     * Check if the user has a permission or permissions.
     *
     * @param int|string|array $permission
     * @param bool             $all
     *
     * @return bool
     */
    public function hasPermission($permission, $all = false)
    {
        if (!$all) {
            return $this->hasOnePermission($permission);
        }

        return $this->hasAllPermissions($permission);
    }

    /**
     * Check if the user has at least one of the given permissions.
     *
     * @param int|string|array $permission
     *
     * @return bool
     */
    public function hasOnePermission($permission)
    {
        foreach ($this->getArrayFrom($permission) as $permission) {
            if ($this->checkPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user has all permissions.
     *
     * @param int|string|array $permission
     *
     * @return bool
     */
    public function hasAllPermissions($permission)
    {
        foreach ($this->getArrayFrom($permission) as $permission) {
            if (!$this->checkPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the user has a permission.
     *
     * @param int|string $permission
     *
     * @return boolean
     */
    public function checkPermission($permission)
    {
        return $this->permissions->contains(function ($value) use ($permission) {
            return $permission == $value->id || Str::is($permission, $value->slug);
        });
    }


    /**
     * Get an array from argument.
     *
     * @param int|string|array $argument
     *
     * @return array
     */
    private function getArrayFrom($argument)
    {
        return app(SplitterInterface::class)->getArrayFrom($argument);
    }











    /**
     * Detach all roles from a user.
     *
     * @return int
     */
    public function detachAllRoles()
    {
        $this->roles = null;

        return $this->roles()->detach();
    }

    /**
     * Sync roles for a user.
     *
     * @param array|\jeremykenedy\LaravelRoles\Model\Role[]|\Illuminate\Database\Eloquent\Collection $roles
     *
     * @return array
     */
    public function syncRoles($roles)
    {
        $this->roles = null;

        return $this->roles()->sync($roles);
    }

    /**
     * Get role level of a user.
     *
     * @return int
     */
    public function level()
    {
        return ($role = $this->getRoles()->sortByDesc('level')->first()) ? $role->level : 0;
    }

    /**
     * Get all permissions from roles.
     *
     * @return Builder
     */
    public function rolePermissions()
    {
        $permissionModel = app(config('roles.models.permission'));

        if (!$permissionModel instanceof Model) {
            throw new \InvalidArgumentException('[roles.models.permission] must be an instance of \Illuminate\Database\Eloquent\Model');
        }

        return $permissionModel
            ::select(['permissions.*', 'permission_role.created_at as pivot_created_at', 'permission_role.updated_at as pivot_updated_at'])
            ->join('permission_role', 'permission_role.permission_id', '=', 'permissions.id')
            ->join('roles', 'roles.id', '=', 'permission_role.role_id')
            ->whereIn('roles.id', $this->getRoles()->pluck('id')->toArray())
            ->orWhere('roles.level', '<', $this->level())
            ->groupBy(['permissions.id', 'permissions.name', 'permissions.slug', 'permissions.description', 'permissions.model', 'permissions.created_at', 'permissions.updated_at', 'permissions.deleted_at', 'pivot_created_at', 'pivot_updated_at']);
    }







    /**
     * Check if the user is allowed to manipulate with entity.
     *
     * @param string $providedPermission
     * @param Model  $entity
     * @param bool   $owner
     * @param string $ownerColumn
     *
     * @return bool
     */
    public function allowed($providedPermission, Model $entity, $owner = true, $ownerColumn = 'user_id')
    {
        if ($this->isPretendEnabled()) {
            return $this->pretend('allowed');
        }

        if ($owner === true && $entity->{$ownerColumn} == $this->id) {
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
     * @return bool
     */
    protected function isAllowed($providedPermission, Model $entity)
    {
        foreach ($this->getPermissions() as $permission) {
            if ($permission->model != '' && get_class($entity) == $permission->model
                && ($permission->id == $providedPermission || $permission->slug === $providedPermission)
            ) {
                return true;
            }
        }

        return false;
    }



    /**
     * Detach all permissions from a user.
     *
     * @return int
     */
    public function detachAllPermissions()
    {
        $this->permissions = null;

        return $this->userPermissions()->detach();
    }

    /**
     * Sync permissions for a user.
     *
     * @param array|\jeremykenedy\LaravelRoles\Model\Permission[]|\Illuminate\Database\Eloquent\Collection $permissions
     *
     * @return array
     */
    public function syncPermissions($permissions)
    {
        $this->permissions = null;

        return $this->userPermissions()->sync($permissions);
    }

    /**
     * Check if pretend option is enabled.
     *
     * @return bool
     */
    private function isPretendEnabled()
    {
        return (bool) config('roles.pretend.enabled');
    }

    /**
     * Allows to pretend or simulate package behavior.
     *
     * @param string $option
     *
     * @return bool
     */
    private function pretend($option)
    {
        return (bool) config('roles.pretend.options.'.$option);
    }



    public function callMagic($method, $parameters)
    {
        if (starts_with($method, 'is')) {
            return $this->hasRole(Str::snake(substr($method, 2), config('roles.separator')));
        } elseif (starts_with($method, 'can')) {
            return $this->hasPermission(Str::snake(substr($method, 3), config('roles.separator')));
        } elseif (starts_with($method, 'allowed')) {
            return $this->allowed(Str::snake(substr($method, 7), config('roles.separator')), $parameters[0], (isset($parameters[1])) ? $parameters[1] : true, (isset($parameters[2])) ? $parameters[2] : 'user_id');
        }

        return parent::__call($method, $parameters);
    }

    public function __call($method, $parameters)
    {
        return $this->callMagic($method, $parameters);
    }
}
