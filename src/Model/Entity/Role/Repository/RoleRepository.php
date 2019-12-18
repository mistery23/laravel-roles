<?php

namespace Mistery23\LaravelRoles\Model\Entity\Role\Repository;

use Mistery23\LaravelRoles\Model\Entity\Role\Role as Model;

/**
 * Class RoleRepository
 */
class RoleRepository implements RoleRepositoryInterface
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
        if (false === $model->flush()) {
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
        if (false === $model->flush()) {
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
