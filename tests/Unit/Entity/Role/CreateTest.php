<?php

namespace Mistery23\LaravelRoles\Test\Unit\Entity\Role;

use Mistery23\LaravelRoles\Test\Builder\Role\RoleBuilder;
use Mistery23\LaravelRoles\Model\Entity\Role\Role;
use Mistery23\LaravelRoles\Test\TestCase;

class CreateTest extends TestCase
{

    public function testNew(): void
    {
        $role = (new RoleBuilder())
            ->withNameAndSlug(
                $name = 'admin',
                $slug = 'admin'
            )
            ->withOptionField(
                $level = 2,
                $desc  = 'Role admin'
            )
            ->build();

        self::assertEquals($name, $role->name);
        self::assertEquals($slug, $role->slug);
        self::assertEquals($level, $role->level);
        self::assertEquals($desc, $role->description);
    }

    public function testNewMin(): void
    {
        $role = (new RoleBuilder())
            ->withNameAndSlug(
                $name = 'admin',
                $slug = 'admin'
            )
            ->build();

        self::assertEquals($name, $role->name);
        self::assertEquals($slug, $role->slug);
        self::assertEquals(Role::$defaultLevel, $role->level);
        self::assertNull($role->description);
    }
}
