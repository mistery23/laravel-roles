<?php

namespace jeremykenedy\LaravelRoles\Model\ReadModels;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use jeremykenedy\LaravelRoles\Model\Entity\Permission\Permission;

class PermissionQueries implements PermissionQueriesInterface
{

    public function getById(string $id): Permission
    {
        $permission = Permission::findOrFail($id);

        return $permission;
    }

    public function getBySlug(string $slug): Permission
    {
        $permission = Permission::where('slug', $slug)->first();

        return $permission;
    }

    public function getAll(int $perPage = 20): LengthAwarePaginator
    {
        $permissions = Permission::withoutTrashed()->orderByDesc('created_at')->paginate($perPage);

        return $permissions;
    }
}
