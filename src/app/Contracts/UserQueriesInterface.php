<?php

namespace Mistery23\LaravelRoles\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserQueriesInterface
{

    public function getById($id);

    public function getAll(int $perPage = 20): LengthAwarePaginator;
}
