<?php

use Illuminate\Database\Seeder;
use Mistery23\LaravelRoles\Model\UseCases\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Permissions
         */
        $permissions = [
            [
                'name'        => 'Portal administrator',
                'slug'        => 'portal.administrator',
                'description' => 'Can administrate portal',
                'model'       => null,
                'parent'      => null
            ],
            [
                'name'        => 'Portal client',
                'slug'        => 'portal.client',
                'description' => 'Can use portal',
                'model'       => null,
                'parent'      => null
            ]
        ];

        /*
         * Add Permission
         */
        foreach ($permissions as $permission) {
            $newPermissionItem = config('roles.models.permission')::where('slug', '=', $permission['slug'])->first();
            if (null === $newPermissionItem) {
                $command = new Permission\Create\Command(
                    $permission['name'],
                    $permission['slug'],
                    $permission['model'],
                    $permission['parent'],
                    $permission['description']
                );

                app(Permission\Create\Handler::class)->handle($command);
            }
        }
    }
}
