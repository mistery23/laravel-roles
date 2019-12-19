<?php

namespace Mistery23\LaravelRoles\Test\Unit\Entity\Role;

use Mistery23\LaravelRoles\Test\Builder\Role\RoleBuilder;
use Mistery23\LaravelRoles\Test\TestCase;
use Ramsey\Uuid\Uuid;

class NestedTest extends TestCase
{

    public function testDoChild(): void
    {
        $role = (new RoleBuilder())
            ->build();

        $role->doChild($id = Uuid::uuid4());

        self::assertEquals($id, $role->parent_id);
    }

    public function testDoRoot(): void
    {
        $role = (new RoleBuilder())
            ->child()
            ->build();

        $role->doRoot();

        self::assertNull($role->parent_id);
    }
}
