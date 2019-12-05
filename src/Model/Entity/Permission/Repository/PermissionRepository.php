<?php

namespace jeremykenedy\LaravelRoles\Model\Entity\Permission\Repository;

use jeremykenedy\LaravelRoles\Model\Entity\Permission\Permission;


class PermissionRepository implements PermissionRepositoryInterface
{

    /**
     * Add role
     *
     * @param Permission $permission
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function add(Permission $permission): void
    {
        if (false === $permission->save()) {
            throw new \RuntimeException('Save error.');
        }
    }

    /**
     * Add user
     *
     * @param Permission $permission
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function update(Permission $permission): void
    {
        if (false === $permission->push()) {
            throw new \RuntimeException('Update error.');
        }
    }

    /**
     * Add role
     *
     * @param Permission $permission
     *
     * @return void
     *
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function remove(Permission $permission): void
    {
        if (false === $permission->delete()) {
            throw new \RuntimeException('Delete error.');
        }
    }
}