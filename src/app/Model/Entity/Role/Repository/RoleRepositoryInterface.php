<?php

namespace Mistery23\LaravelRoles\Model\Entity\Role\Repository;

use Mistery23\LaravelRoles\Model\Entity\Role\Role;

interface RoleRepositoryInterface
{

    public function add(Role $role);

    public function update(Role $role);

    public function remove(Role $role);
}
