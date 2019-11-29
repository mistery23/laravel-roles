<?php

namespace jeremykenedy\LaravelRoles\Model\Entity\Role\Repository;

use jeremykenedy\LaravelRoles\Model\Entity\Role\Role;

interface RoleRepositoryInterface
{

    public function add(Role $role);

    public function update(Role $role);

    public function remove(Role $role);
}
