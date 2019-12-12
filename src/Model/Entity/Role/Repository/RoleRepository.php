<?php

namespace Mistery23\LaravelRoles\Model\Entity\Role\Repository;

use Mistery23\LaravelRoles\Model\Entity\Role\Role;


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
        if (false === $role->push()) {
            throw new \RuntimeException('Save error.');
        }
    }

    /**
     * Update role
     *
     * @param Role $role
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function update(Role $role): void
    {
        if (false === $role->push()) {
            throw new \RuntimeException('Update error.');
        }
    }

    /**
     * Remove role
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