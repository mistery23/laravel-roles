<?php

namespace Mistery23\LaravelRoles\Model\ReadModels;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Mistery23\LaravelRoles\Model\Entity\Role\Role;

interface RoleQueriesInterface
{

    public function getById(string $id): Role;

    public function getBySlug(string $slug): Role;

    public function hasByNameAndSlug(string $name, string $slug): bool;

    public function exists(string $id): bool;

    public function getAll(int $perPage = 20): LengthAwarePaginator;

    public function getAllPermissions(string $roleId, int $perPage = 20): LengthAwarePaginator;
}