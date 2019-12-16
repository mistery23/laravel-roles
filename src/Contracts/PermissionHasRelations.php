<?php

namespace Mistery23\LaravelRoles\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Interface PermissionHasRelations
 */
interface PermissionHasRelations
{
    /**
     * Permission belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany;

    /**
     * Permission belongs to many users.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany;
}
