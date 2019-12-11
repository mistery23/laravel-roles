<?php

namespace jeremykenedy\LaravelRoles\Model\ReadModels;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use jeremykenedy\LaravelRoles\Model\Entity\Role\Role;

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
}
