<?php

declare(strict_types=1);

namespace jeremykenedy\LaravelRoles\Model\UseCases\User\Attach\Permission;

/**
 * Class Command
 */
class Command
{

    /**
     * @var string
     */
    public $userId;

    /**
     * @var string
     */
    public $permissionId;


    /**
     * Command constructor.
     *
     * @param string $userId
     * @param string $permissionId
     */
    public function __construct(string $userId, string $permissionId)
    {
        $this->userId       = $userId;
        $this->permissionId = $permissionId;
    }
}
