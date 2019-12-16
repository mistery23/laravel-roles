<?php

namespace Mistery23\LaravelRoles\Model\Entity\Role\Repository;

use Mistery23\LaravelRoles\Model\Entity\Role\Role as Model;

/**
 * Interface RoleRepositoryInterface
 */
interface RoleRepositoryInterface
{

    /**
     * @param Model $model
     *
     * @return void
     */
    public function add(Model $model): void;

    /**
     * @param Model $model
     *
     * @return void
     */
    public function update(Model $model): void;

    /**
     * @param Model $model
     *
     * @return void
     */
    public function remove(Model $model): void;
}
