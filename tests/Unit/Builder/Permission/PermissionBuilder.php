<?php

namespace Mistery23\LaravelRoles\Test\Unit\Builder\Permission;

use Mistery23\LaravelRoles\Model\Entity\Permission\Permission;
use Ramsey\Uuid\Uuid;

class PermissionBuilder
{
    private $id;
    private $name;
    private $slug;
    private $description;

    private $parent;


    /**
     * RoleBuilder constructor.
     */
    public function __construct()
    {
        $this->id          = Uuid::uuid4();
        $this->name        = 'user create';
        $this->slug        = 'user.create';
    }

    public function withOptionField(string $description)
    {
        $clone = clone $this;

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

    public function build(): Permission
    {
        return Permission::new(
            $this->id,
            $this->name,
            $this->slug,
            null,
            null,
            $this->description
        );
    }
}
