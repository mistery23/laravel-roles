<?php

namespace Mistery23\LaravelRoles\Console\Commands;

use Illuminate\Console\Command;
use Mistery23\LaravelRoles\Model\UseCases\Permission;
use Mistery23\LaravelRoles\Model\UseCases\Role;
use Webmozart\Assert\Assert;

/**
 * Class Swagger
 */
class Roles extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:gen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create role and permission from a config';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() : void
    {
        $this->permissions(config('roles-seed.permissions'));
        $this->roles(config('roles-seed.roles'));
    }

    /**
     * @param array $permissions
     *
     * @return void
     */
    protected function permissions(array $permissions): void
    {
        foreach ($permissions as $configSlug => $configPerm) {
            $isPresent = config('roles.models.permission')::where('slug', '=', $configSlug)->first();

            if (null === $isPresent) {
                $command = new Permission\Create\Command(
                    $configPerm['name'],
                    $configSlug,
                    $configPerm['model'],
                    null,
                    $configPerm['description']
                );

                app(Permission\Create\Handler::class)->handle($command);
            }

            if (null !== $configPerm['parent']) {
                $perm = config('roles.models.permission')::where('slug', '=', $configSlug)->first();
                $parent = config('roles.models.permission')::where('slug', '=', $configPerm['parent'])->first();

                try {
                    $command = new Permission\DoChild\Command($perm->id, $parent->id);

                    app(Permission\DoChild\Handler::class)->handle($command);
                } catch (\InvalidArgumentException $e) {
                    echo 'Permission ' . $perm->slug . ' already child.' . PHP_EOL;
                }
            }
        }
    }

    /**
     * @param array $roles
     *
     * @return void
     */
    protected function roles(array $roles): void
    {
        foreach ($roles as $configSlug => $configRole) {
            $isPresent = config('roles.models.role')::where('slug', '=', $configSlug)->first();

            if (null === $isPresent) {
                $command = new Role\Create\Command(
                    $configRole['name'],
                    $configSlug,
                    $configRole['level'],
                    $configRole['description']
                );

                app(Role\Create\Handler::class)->handle($command);
            }

            if (null !== $configRole['parent']) {
                $role = config('roles.models.role')::where('slug', '=', $configSlug)->first();
                $parent = config('roles.models.role')::where('slug', '=', $configRole['parent'])->first();

                try {
                    $command = new Role\DoChild\Command($role->id, $parent->id);

                    app(Role\DoChild\Handler::class)->handle($command);
                } catch (\InvalidArgumentException $e) {
                    echo 'Permission ' . $role->slug . ' already child.' . PHP_EOL;
                }
            }

            if ([] !== $configRole['permissions']) {
                $role = config('roles.models.role')::where('slug', '=', $configSlug)->first();

                foreach ($configRole['permissions'] as $attachingPerm) {
                    $perm = config('roles.models.permission')::where('slug', '=', $attachingPerm)->first();
                    Assert::notNull($perm, 'Perm ' . $attachingPerm . ' not found.');


                    try {
                        $command = new Role\Attach\Permission\Command($role->id, $perm->id);

                        app(Role\Attach\Permission\Handler::class)->handle($command);
                    } catch (\InvalidArgumentException $e) {
                        echo 'Permission ' . $role->slug . ' already attached to role ' . $configSlug . '.' . PHP_EOL;
                    }
                }
            }
        }
    }
}
