<?php

use Illuminate\Database\Seeder;
use Mistery23\LaravelRoles\Model\UseCases\Role;

class RolesSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Roles
         */
        $roles = [
            [
                'name'        => 'Root',
                'slug'        => 'root',
                'description' => 'Root role',
                'level'       => 100,
            ],
            [
                'name'        => 'Admin',
                'slug'        => 'admin',
                'description' => 'Admin role',
                'level'       => 90,
            ],
            [
                'name'        => 'Manager',
                'slug'        => 'manager',
                'description' => 'Manager role',
                'level'       => 70,
            ],
        ];

        /*
         * Add Roles
         */
        foreach ($roles as $role) {
            $newRoleItem = config('roles.models.role')::where('slug', '=', $role['slug'])->first();
            if (null === $newRoleItem) {
                $command = new Role\Create\Command(
                    $role['name'],
                    $role['slug'],
                    $role['level'],
                    $role['description'],
                );

                app(Role\Create\Handler::class)->handle($command);
            }
        }
    }
}
