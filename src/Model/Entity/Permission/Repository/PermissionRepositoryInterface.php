<?php

declare(strict_types=1);

namespace Mistery23\LaravelRoles\Model\Entity\Permission\Repository;

use Mistery23\LaravelRoles\Model\Entity\Permission\Permission as Model;

/**
 * Interface PermissionRepositoryInterface
 */
interface PermissionRepositoryInterface
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
