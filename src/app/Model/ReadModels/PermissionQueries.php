<?php

namespace Mistery23\LaravelRoles\Model\ReadModels;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Mistery23\LaravelRoles\Model\Entity\Permission\Permission;

class PermissionQueries implements PermissionQueriesInterface
{

    public function getById(string $id): Permission
    {
        $permission = Permission::findOrFail($id);

        return $permission;
    }

    public function getByIdWithChildren(string $id): Collection
    {
        $permissions = Permission::with('descendantsAndSelf')
            ->where('id', $id)
            ->first()
            ->descendantsAndSelf()
            ->get();

        return $permissions;
    }

    public function getBySlug(string $slug): Permission
    {
        $permission = Permission::where('slug', $slug)->first();

        return $permission;
    }

    public function exists(string $id): bool
    {
        $permission = Permission::where('id', $id)->exists();

        return $permission;
    }

    public function getAll(int $perPage = 20): LengthAwarePaginator
    {
        $permissions = Permission::withoutTrashed()->orderByDesc('created_at')->paginate($perPage);

        return $permissions;
    }

    public function hasRolePermission(string $userId, string $permissionId): bool
    {
        $perm = app(config('roles.models.defaultUser'))::where(config('roles.usersTable') . '.id', $userId)
            ->leftJoin(
                config('roles.roleUserTable'),
                config('roles.usersTable') . '.id',
                '=',
                config('roles.roleUserTable') . '.user_id'
            )
            ->leftJoin(
                config('roles.rolesTable'),
                config('roles.roleUserTable') . '.role_id',
                '=',
                config('roles.rolesTable') . '.id'
            )
            ->leftJoin(
                config('roles.permissionsRoleTable'),
                config('roles.rolesTable') . '.id',
                '=',
                config('roles.permissionsRoleTable') . '.role_id'
            )
            ->leftJoin(
                config('roles.permissionsTable'),
                config('roles.permissionsRoleTable') . '.permission_id',
                '=',
                config('roles.permissionsTable') . '.id'
            )
            ->where(config('roles.permissionsTable') . '.slug', $permissionId)
            ->count();

        return $perm > 0;
    }

    public function hasUserPermission(string $userId, string $permissionId)
    {
        $perm = app(config('roles.models.defaultUser'))::where(config('roles.usersTable') . '.id', $userId)
            ->leftJoin(
                config('roles.permissionsUserTable'),
                config('roles.usersTable') . '.id',
                '=',
                config('roles.permissionsUserTable') . '.user_id'
            )
            ->leftJoin(
                config('roles.permissionsTable'),
                config('roles.permissionsUserTable') . '.permission_id',
                '=',
                config('roles.permissionsTable') . '.id'
            )
            ->where(config('roles.permissionsTable') . '.slug', $permissionId)
            ->count();

        return $perm > 0;
    }
}
