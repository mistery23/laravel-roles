<?php

declare(strict_types=1);

namespace Mistery23\LaravelRoles\Model\UseCases\Role\Create;

/**
 * Class Command
 */
class Command
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var integer
     */
    public $level;

    /**
     * @var string|null
     */
    public $description;


    /**
     * Command constructor.
     *
     * @param string $name
     * @param string $slug
     * @param integer $level
     * @param string|null $description
     */
    public function __construct(string $name, string $slug, int $level, ?string $description = null)
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->level = $level;
        $this->description = $description;
    }
}
