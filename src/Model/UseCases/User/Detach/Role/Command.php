<?php

declare(strict_types=1);

namespace jeremykenedy\LaravelRoles\Model\UseCases\User\Detach\Role;


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
    public $roleId;


    /**
     * Command constructor.
     *
     * @param string $userId
     * @param string $roleId
     */
    public function __construct(string $userId, string $roleId)
    {
        $this->userId = $userId;
        $this->roleId = $roleId;
    }
}
