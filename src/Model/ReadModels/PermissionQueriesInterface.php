<?php

namespace jeremykenedy\LaravelRoles\Model\ReadModels;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use jeremykenedy\LaravelRoles\Model\Entity\Permission\Permission;

interface PermissionQueriesInterface
{

    public function getById(string $id): Permission;

    public function getBySlug(string $slug): Permission;

    public function getAll(int $perPage = 20): LengthAwarePaginator;
}