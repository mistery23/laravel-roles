<?php

namespace jeremykenedy\LaravelRoles\Model\ReadModels;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use jeremykenedy\LaravelRoles\Model\Entity\Permission\Permission;

interface PermissionQueriesInterface
{

    public function getById(string $id): Permission;

    public function getByIdWithChildren(string $id): Collection;

    public function getBySlug(string $slug): Permission;

    public function exists(string $id): bool;

    public function getAll(int $perPage = 20): LengthAwarePaginator;

    public function hasRolePermission(string $userId, string $permissionId): bool;
}