<?php

namespace Mistery23\LaravelRoles\Model\UseCases\Role\Detach\Permission;


/**
 * Class Command
 */
class Command
{

    /**
     * @var string
     */
    public $roleId;

    /**
     * @var string
     */
    public $permissionId;

    /**
     * Command constructor.
     *
     * @param string $roleId
     * @param string $permissionId
     */
    public function __construct(string $roleId, string $permissionId)
    {
        $this->roleId = $roleId;
        $this->permissionId = $permissionId;
    }
}