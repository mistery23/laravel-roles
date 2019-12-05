<?php

namespace jeremykenedy\LaravelRoles\Model\UseCases\Permission\DoRoot;

/**
 * Class Command
 */
class Command
{

    /**
     * @var string
     */
    public $id;


    /**
     * Command constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
