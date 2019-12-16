<?php

namespace Mistery23\LaravelRoles\Model\Entity\Permission\Repository;

use Mistery23\LaravelRoles\Model\Entity\Permission\Permission as Model;

/**
 * Class PermissionRepository
 */
class PermissionRepository implements PermissionRepositoryInterface
{

    /**
     * @param Model $model
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function add(Model $model): void
    {
        if (false === $model->save()) {
            throw new \RuntimeException('Save error.');
        }
    }

    /**
     * @param Model $model
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function update(Model $model): void
    {
        if (false === $model->push()) {
            throw new \RuntimeException('Update error.');
        }
    }

    /**
     * @param Model $model
     *
     * @return void
     *
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function remove(Model $model): void
    {
        if (false === $model->delete()) {
            throw new \RuntimeException('Delete error.');
        }
    }
}
