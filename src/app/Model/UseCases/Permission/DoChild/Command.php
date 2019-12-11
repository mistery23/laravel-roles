<?php

namespace Mistery23\LaravelRoles\Model\UseCases\Permission\DoChild;

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
     * @var string
     */
    public $parentId;


    /**
     * Command constructor.
     *
     * @param string $id
     * @param string $parentId
     */
    public function __construct(string $id, string $parentId)
    {
        $this->id       = $id;
        $this->parentId = $parentId;
    }
}
