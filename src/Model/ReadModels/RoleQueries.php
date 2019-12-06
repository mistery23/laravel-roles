<?php

namespace jeremykenedy\LaravelRoles\Model\ReadModels;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use jeremykenedy\LaravelRoles\Model\Entity\Role\Role;

class RoleQueries implements RoleQueriesInterface
{

    public function getById(string $id): Role
    {
        $role = Role::findOrFail($id);

        return $role;
    }

    public function getBySlug(string $slug): Role
    {
        $role = Role::where('slug', $slug)->first();

        return $role;
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
        $roles = Role::withoutTrashed()->orderByDesc('created_at')->paginate($perPage);

        return $roles;
    }

    public function getAllWithPermissions(int $perPage = 20): LengthAwarePaginator
    {
        $roles = Role::withoutTrashed()->with('permissions')->orderByDesc('created_at')->paginate($perPage);

        return $roles;
    }
}
