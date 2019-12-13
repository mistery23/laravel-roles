<?php

namespace Mistery23\LaravelRoles\Model\ReadModels;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Mistery23\LaravelRoles\Model\Entity\Role\Role;

class RoleQueries implements RoleQueriesInterface
{

    public function getById(string $id): Role
    {
        return Role::findOrFail($id);
    }

    public function getBySlug(string $slug): Role
    {
        return Role::where('slug', $slug)->first();
    }

    public function exists(string $id): bool
    {
        return Role::where('id', $id)->exists();
    }

    public function hasByNameAndSlug(string $name, string $slug): bool
    {
        $role = Role::where('name', $name)
            ->orWhere('slug', $slug)
            ->count();

        return $role > 0;
    }

    public function getAll(int $perPage = 20): LengthAwarePaginator
    {
        return Role::withoutTrashed()->orderBy('level')->paginate($perPage);
    }

    public function getAllPermissions(string $roleId, int $perPage = 20): LengthAwarePaginator
    {
        return Role::withoutTrashed()
            ->with('permissions.descendants')
            ->where(config('roles.rolesTable') . '.id', $roleId)
            ->orderBy('level')
            ->paginate($perPage);
    }

    public function hasRole(string $userId, string $role): bool
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
            ->groupBy([
                'tree.id',
                'tree.name',
                'tree.slug',
                'tree.parent_id',
            ])
            ->having('tree.slug', $role)
            ->count();

        return $tree > 0;
    }
}
