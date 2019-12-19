<?php

namespace Mistery23\LaravelRoles\Test\Unit\Entity\Role;

use Mistery23\LaravelRoles\Test\Builder\Role\RoleBuilder;
use Mistery23\LaravelRoles\Test\TestCase;

class EditTest extends TestCase
{

    public function testEdit(): void
    {
        $role = (new RoleBuilder())
            ->build();

        $role->edit(
            $name = 'admin',
            $slug = 'admin',
            $level = 2,
            $desc = 'Admin role'
        );

        self::assertEquals($name, $role->name);
        self::assertEquals($slug, $role->slug);
        self::assertEquals($level, $role->level);
        self::assertEquals($desc, $role->description);
    }

    public function testEditMin(): void
    {
        $role = (new RoleBuilder())
            ->withOptionField(
                $level = 2,
                $desc  = 'Role admin'
            )
            ->build();

        $role->edit(
            $name = 'admin',
            $slug = 'admin'
        );

        self::assertEquals($name, $role->name);
        self::assertEquals($slug, $role->slug);
        self::assertEquals($level, $role->level);
        self::assertEquals($desc, $role->description);
    }
}
