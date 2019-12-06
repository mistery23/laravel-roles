<?php

namespace jeremykenedy\LaravelRoles\Model\UseCases\Role\Copy;

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
    public $name;

    /**
     * @var string
     */
    public $slug;


    /**
     * Command constructor.
     *
     * @param string $roleId
     * @param string $name
     * @param string $slug
     */
    public function __construct(string $roleId, string $name, string $slug)
    {
        $this->roleId = $roleId;
        $this->name   = $name;
        $this->slug   = $slug;
    }
}
