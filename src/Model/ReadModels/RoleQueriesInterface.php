<?php

namespace Mistery23\LaravelRoles\Model\ReadModels;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Mistery23\LaravelRoles\Model\Entity\Role\Role as Model;

/**
 * Interface RoleQueriesInterface
 */
interface RoleQueriesInterface
{


    /**
     * @param string $id
     *
     * @return Model
     */
    public function getById(string $id): Model;

    /**
     * @param string $slug
     *
     * @return Model
     */
    public function getBySlug(string $slug): Model;

    /**
     * @param string $name
     * @param string $slug
     *
     * @return boolean
     */
    public function hasByNameAndSlug(string $name, string $slug): bool;

    /**
     * @param string $id
     *
     * @return boolean
     */
    public function exists(string $id): bool;

    /**
     * @param integer $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAll(int $perPage = 20): LengthAwarePaginator;

    /**
     * @param string $roleId
     * @param integer $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllPermissions(string $roleId, int $perPage = 20): LengthAwarePaginator;
}
