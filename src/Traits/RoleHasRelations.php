<?php

namespace Mistery23\LaravelRoles\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Mistery23\LaravelRoles\Model\Entity\Permission\Permission;
use Mistery23\LaravelRoles\Model\Entity\RolePermission;
use Webmozart\Assert\Assert;

/**
 * Trait RoleHasRelations
 */
trait RoleHasRelations
{
    /**
     * Role belongs to many permissions.
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('roles.models.permission'),
            config('roles.models.permissionRole')
        )
            ->withTimestamps();
    }

    /**
     * Role belongs to many users.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            config('roles.models.defaultUser'),
            config('roles.models.userRole')
        )
            ->withTimestamps();
    }

    /**
     * Attach permission to a role.
     *
     * @param string $permissionId
     */
    public function attachPermission($permissionId): void
    {
        $flag = $this->permissions->contains($permissionId);

        Assert::false($flag, 'Permission is already attached');

        $this->permissions->add(RolePermission::new($this->id, $permissionId));
    }

    /**
     * Detach permission from a role.
     *
     * @param string $permissionId
     */
    public function detachPermission($permissionId)
    {
        $flag = $this->permissions->contains($permissionId);

        Assert::true($flag, 'Permission is not attached');

        $this->detachItem('permissions', $permissionId);
    }

    /**
     * Detach all permissions.
     *
     * @return int
     */
    public function detachAllPermissions()
    {
        return $this->permissions()->detach();
    }

    /**
     * Sync permissions for a role.
     *
     * @param array|Permission[]|Collection $permissions
     *
     * @return array
     */
    public function syncPermissions($permissions)
    {
        return $this->permissions()->sync($permissions);
    }
}
