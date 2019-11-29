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

    public function getAll(int $perPage = 20): LengthAwarePaginator
    {
        $roles = Role::withoutTrashed()->orderByDesc('created_at')->paginate($perPage);

        return $roles;
    }
}
