<?php

namespace Mistery23\LaravelRoles\Test;

use Mistery23\LaravelRoles\RolesServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    protected function getPackageProviders($app)
    {
        return [RolesServiceProvider::class];
    }
}
