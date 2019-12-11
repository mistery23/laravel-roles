<?php

namespace Mistery23\LaravelRoles\Test\Unit\Entity\Permission;

use Mistery23\LaravelRoles\Test\TestCase;
use Mistery23\LaravelRoles\Test\Unit\Builder\Permission\PermissionBuilder;

class CreateTest extends TestCase
{

    public function testNew(): void
    {
        $perm = (new PermissionBuilder())
            ->withNameAndSlug(
                $name = 'user create',
                $slug = 'user.create'
            )
            ->withOptionField(
                $desc  = 'User create'
            )
            ->build();

        self::assertEquals($name, $perm->name);
        self::assertEquals($slug, $perm->slug);
        self::assertEquals($desc, $perm->description);
    }

    public function testNewMin(): void
    {
        $perm = (new PermissionBuilder())
            ->withNameAndSlug(
                $name = 'admin',
                $slug = 'admin'
            )
            ->build();

        self::assertEquals($name, $perm->name);
        self::assertEquals($slug, $perm->slug);
        self::assertNull($perm->description);
    }
}
