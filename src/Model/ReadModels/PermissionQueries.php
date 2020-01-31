<?php

namespace Mistery23\LaravelRoles\Model\ReadModels;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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

    public function getPermissionsRootWithChildren(int $perPage = 20): LengthAwarePaginator
    {
        return Permission::with('children')
            ->whereNull('parent_id')
            ->orderBy('created_at')
            ->paginate($perPage);
    }

    public function getPermissionChildren(string $permissionId, int $perPage = 20): LengthAwarePaginator
    {
        return Permission::with('children')
            ->where('parent_id', '=', $permissionId)
            ->orderBy('created_at')
            ->paginate($perPage);
    }

    public function hasRolePermission(string $userId, string $permission): bool
    {
        $query = Db::table(config('roles.rolesTable'))
            ->select([
                config('roles.rolesTable') . '.id',
                config('roles.rolesTable') . '.name',
                config('roles.rolesTable') . '.slug',
                config('roles.rolesTable') . '.parent_id',
            ]);

        Db::table(config('roles.usersTable'))
            ->select([config('roles.roleUserTable') . '.role_id'])
            ->leftJoin(
                config('roles.roleUserTable'),
                config('roles.usersTable') . '.id',
                '=',
                config('roles.roleUserTable') . '.user_id'
            )
            ->where(config('roles.roleUserTable') . '.user_id', $userId)
            ->get()
            ->map(static function ($value) use (&$query) {
                $query->where(config('roles.rolesTable') . '.id', $value->role_id);
            });

        $query->whereNull(config('roles.rolesTable') . '.deleted_at')
            ->unionAll(
                DB::table(config('roles.rolesTable'))
                    ->select([
                        config('roles.rolesTable') . '.id',
                        config('roles.rolesTable') . '.name',
                        config('roles.rolesTable'). '.slug',
                        config('roles.rolesTable') . '.parent_id',
                    ])
                    ->whereNull(config('roles.rolesTable') . '.deleted_at')
                    ->join('tree', 'tree.id', '=', config('roles.rolesTable') . '.parent_id')
            );

        $tree = DB::table('tree')
            ->withRecursiveExpression('tree', $query)
            ->where('slug', 'admin')
            ->get();

        foreach ($tree as $role) {
            $query = Db::table(config('roles.permissionsTable'))
                ->select([
                    config('roles.permissionsTable') . '.id',
                    config('roles.permissionsTable') . '.name',
                    config('roles.permissionsTable') . '.slug',
                    config('roles.permissionsTable') . '.parent_id',
                ]);

            Db::table(config('roles.permissionsTable'))
                ->select([
                    config('roles.permissionsTable') . '.id',
                    config('roles.permissionsTable') . '.slug'
                ])
                ->leftJoin(
                    config('roles.permissionsRoleTable'),
                    config('roles.permissionsTable') . '.id',
                    '=',
                    config('roles.permissionsRoleTable') . '.permission_id'
                )
                ->where(config('roles.permissionsRoleTable') . '.role_id', $role->id)
                ->whereNull(config('roles.permissionsTable') . '.deleted_at')
                ->get()
                ->map(static function ($perm) use (&$query) {
                    $query->orWhere(config('roles.permissionsTable') . '.id', $perm->id);
                });

            $query->whereNull(config('roles.permissionsTable') . '.deleted_at');

            $query->unionAll(
                DB::table(config('roles.permissionsTable'))
                    ->select([
                        config('roles.permissionsTable') . '.id',
                        config('roles.permissionsTable') . '.name',
                        config('roles.permissionsTable'). '.slug',
                        config('roles.permissionsTable') . '.parent_id',
                    ])
                    ->whereNull(config('roles.permissionsTable') . '.deleted_at')
                    ->join('tree', 'tree.id', '=', config('roles.permissionsTable') . '.parent_id')
            );

            $tree = DB::table('tree')
                ->withRecursiveExpression('tree', $query)
                ->groupBy([
                    'tree.id',
                    'tree.name',
                    'tree.slug',
                    'tree.parent_id',
                ])
                ->having('tree.slug', $permission)
                ->count();

            if ($tree > 0) {
                return true;
            }
        }

        return false;
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
