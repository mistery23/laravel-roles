<?php

namespace Mistery23\LaravelRoles\Model\UseCases\Permission\Remove;


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