<?php

namespace Mistery23\LaravelRoles\Test\Builder\Role;

use Mistery23\LaravelRoles\Model\Entity\Role\Role;
use Ramsey\Uuid\Uuid;

class RoleBuilder
{
    private $id;
    private $name;
    private $slug;
    private $level;
    private $description;

    private $parent;


    /**
     * RoleBuilder constructor.
     */
    public function __construct()
    {
        $this->id          = Uuid::uuid4();
        $this->name        = 'root';
        $this->slug        = 'root';
    }

    public function withOptionField(int $level, string $description)
    {
        $clone = clone $this;

        $clone->level       = $level;
        $clone->description = $description;

        return $clone;
    }

    public function withNameAndSlug(string $name, string $slug)
    {
        $clone = clone $this;

        $clone->name = $name;
        $clone->slug = $slug;

        return $clone;
    }

    public function child()
    {
        $clone = clone $this;

        $clone->parent = Uuid::uuid4();

        return $clone;
    }

    public function build(): Role
    {
        $role = Role::new(
            $this->id,
            $this->name,
            $this->slug,
            $this->level,
            $this->description
        );

        if ($this->parent) {
            $role->doChild($this->parent);
        }

        return $role;
    }
}
