<?php

namespace Mistery23\LaravelRoles\Test\Unit\Role\Entity;

use Mistery23\LaravelRoles\Model\Entity\Role\Role;
use Mistery23\LaravelRoles\Test\TestCase;
use Mistery23\LaravelRoles\Test\Unit\Builder\Role\RoleBuilder;
use Ramsey\Uuid\Uuid;

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
        self::isNull($role->description);
    }
}
