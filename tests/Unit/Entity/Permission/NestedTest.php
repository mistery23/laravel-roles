<?php

namespace Mistery23\LaravelRoles\Test\Unit\Entity\Permission;

use Mistery23\LaravelRoles\Test\Builder\Permission\PermissionBuilder;
use Mistery23\LaravelRoles\Test\TestCase;
use Ramsey\Uuid\Uuid;

class NestedTest extends TestCase
{

    public function testDoChild(): void
    {
        $role = (new PermissionBuilder())
            ->build();

        $role->doChild($id = Uuid::uuid4());

        self::assertEquals($id, $role->parent_id);
    }

    public function testDoRoot(): void
    {
        $role = (new PermissionBuilder())
            ->child()
            ->build();

        $role->doRoot();

        self::assertNull($role->parent_id);
    }
}
