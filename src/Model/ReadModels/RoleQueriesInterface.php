<?php

namespace jeremykenedy\LaravelRoles\Model\ReadModels;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use jeremykenedy\LaravelRoles\Model\Entity\Role\Role;

interface RoleQueriesInterface
{

    public function getById(string $id): Role;

    public function getBySlug(string $slug): Role;

    public function getAll(int $perPage = 20): LengthAwarePaginator;
}