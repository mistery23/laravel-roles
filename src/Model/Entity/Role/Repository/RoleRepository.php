<?php

namespace jeremykenedy\LaravelRoles\Model\Entity\Role\Repository;

use jeremykenedy\LaravelRoles\Model\Entity\Role\Role;


class RoleRepository implements RoleRepositoryInterface
{

    /**
     * Add role
     *
     * @param Role $role
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function add(Role $role): void
    {
        if (false === $role->save()) {
            throw new \RuntimeException('Save error.');
        }
    }

    /**
     * Add user
     *
     * @param Role $role
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function update(Role $role): void
    {
        if (false === $role->save()) {
            throw new \RuntimeException('Update error.');
        }
    }

    /**
     * Add role
     *
     * @param Role $role
     *
     * @return void
     *
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function remove(Role $role): void
    {
        if (false === $role->delete()) {
            throw new \RuntimeException('Delete error.');
        }
    }
}