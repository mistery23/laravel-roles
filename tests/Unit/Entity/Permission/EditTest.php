<?php

namespace Mistery23\LaravelRoles\Test\Unit\Entity\Permission;

use Mistery23\LaravelRoles\Test\TestCase;
use Mistery23\LaravelRoles\Test\Unit\Builder\Permission\PermissionBuilder;

class EditTest extends TestCase
{

    public function testEdit(): void
    {
        $role = (new PermissionBuilder())
            ->build();

        $role->edit(
            $name = 'user create',
            $slug = 'user.create',
            null,
            null,
            $desc = 'User create'
        );

        self::assertEquals($name, $role->name);
        self::assertEquals($slug, $role->slug);
        self::assertEquals($desc, $role->description);
    }

    public function testEditMin(): void
    {
        $role = (new PermissionBuilder())
            ->withOptionField(
                $desc  = 'User create'
            )
            ->build();

        $role->edit(
            $name = 'user create',
            $slug = 'user.create'
        );

        self::assertEquals($name, $role->name);
        self::assertEquals($slug, $role->slug);
        self::assertEquals($desc, $role->description);
    }
}
