<?php

namespace Mistery23\LaravelRoles\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface UserQueriesInterface
 */
interface UserQueriesInterface
{

    /**
     * @param $id
     *
     * @return Model
     */
    public function getById($id): Model;

    /**
     * @param integer $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAll(int $perPage = 20): LengthAwarePaginator;
}
