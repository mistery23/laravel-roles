<?php

namespace Mistery23\LaravelRoles\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait PermissionHasRelations
{
    /**
     * Permission belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('roles.models.role')
        )
            ->withTimestamps();
    }

    /**
     * Permission belongs to many users.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            config('roles.models.defaultUser')
        )
            ->withTimestamps();
    }
}
