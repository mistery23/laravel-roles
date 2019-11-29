<?php

namespace jeremykenedy\LaravelRoles\Model\UseCases\Permission\Edit;


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
    public $name;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var string
     */
    public $model = null;

    /**
     * @var string
     */
    public $parentId = null;

    /**
     * @var string|null
     */
    public $description = null;


    /**
     * Command constructor.
     *
     * @param string $id
     * @param string $name
     * @param string $slug
     * @param string|null $model
     * @param string|null $parentId
     * @param string|null $description
     */
    public function __construct(
        string $id,
        string $name,
        string $slug,
        ?string $model,
        ?string $parentId,
        ?string $description
    ) {
        $this->id          = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->model = $model;
        $this->parentId = $parentId;
        $this->description = $description;
    }

}