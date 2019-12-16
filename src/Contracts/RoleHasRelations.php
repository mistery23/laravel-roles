<?php

declare(strict_types=1);

namespace Mistery23\LaravelRoles\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Interface RoleHasRelations
 */
interface RoleHasRelations
{

    /**
     * Role belongs to many permissions.
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany;

    /**
     * Role belongs to many users.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany;

    /**
     * Attach permission to a role.
     *
     * @param string $permission
     *
     * @return void
     */
    public function attachPermission($permission): void;

    /**
     * Detach permission from a role.
     *
     * @param string $permission
     *
     * @return void
     */
    public function detachPermission($permission): void;
}
