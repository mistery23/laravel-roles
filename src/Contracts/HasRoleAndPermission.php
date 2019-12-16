<?php

namespace Mistery23\LaravelRoles\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface HasRoleAndPermission
{

    /**
     * User belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany;

    /**
     * Get all roles as collection.
     *
     * @return Collection
     */
    public function getRoles(): Collection;

    /**
     * Check if the user has a role or roles.
     *
     * @param integer|string|array $role
     * @param boolean              $all
     *
     * @return boolean
     */
    public function hasRole($role, $all = false): bool;

    /**
     * Check if the user has at least one of the given roles.
     *
     * @param integer|string|array $role
     *
     * @return boolean
     */
    public function hasOneRole($role): bool;

    /**
     * Check if the user has all roles.
     *
     * @param integer|string|array $role
     *
     * @return boolean
     */
    public function hasAllRoles($role): bool;

    /**
     * Check if the user has role.
     *
     * @param integer|string $role
     *
     * @return boolean
     */
    public function checkRole($role): bool;

    /**
     * Attach role to a user.
     *
     * @param integer|string $roleId
     *
     * @return void
     */
    public function attachRole($roleId): void;

    /**
     * Detach role from a user.
     *
     * @param integer|string $roleId
     *
     * @return void
     */
    public function detachRole($roleId): void;

    /**
     * Get role level of a user.
     *
     * @return int
     */
    public function level();

    /**
     * Check if the user has a permission or permissions.
     *
     * @param int|string|array $permission
     * @param bool             $all
     *
     * @return bool
     */
    public function hasPermission($permission, $all = false);

    /**
     * Check if the user has at least one of the given permissions.
     *
     * @param int|string|array $permission
     *
     * @return bool
     */
    public function hasOnePermission($permission);

    /**
     * Check if the user has all permissions.
     *
     * @param int|string|array $permission
     *
     * @return bool
     */
    public function hasAllPermissions($permission);

    /**
     * Check if the user has a permission.
     *
     * @param int|string $permission
     *
     * @return bool
     */
    public function checkPermission($permission);

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
    public function allowed($providedPermission, Model $entity, $owner = true, $ownerColumn = 'user_id');

    /**
     * Attach permission to a user.
     *
     * @param string $permission
     *
     * @return void
     */
    public function attachPermission($permission);

    /**
     * Detach permission from a user.
     *
     * @param string $permission
     *
     * @return void
     */
    public function detachPermission($permission);
}
