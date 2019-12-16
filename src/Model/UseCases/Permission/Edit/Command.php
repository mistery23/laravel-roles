<?php

declare(strict_types=1);

namespace Mistery23\LaravelRoles\Model\UseCases\Permission\Edit;

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
     * @var string|null
     */
    public $model;

    /**
     * @var string|null
     */
    public $parentId;

    /**
     * @var string|null
     */
    public $description;


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
        ?string $model = null,
        ?string $parentId = null,
        ?string $description = null
    ) {
        $this->id          = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->model = $model;
        $this->parentId = $parentId;
        $this->description = $description;
    }
}
