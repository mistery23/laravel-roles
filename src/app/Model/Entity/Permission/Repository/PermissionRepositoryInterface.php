<?php

namespace Mistery23\LaravelRoles\Model\Entity\Permission\Repository;

use Mistery23\LaravelRoles\Model\Entity\Permission\Permission;

interface PermissionRepositoryInterface
{

    public function add(Permission $role);

    public function update(Permission $role);

    public function remove(Permission $role);
}
