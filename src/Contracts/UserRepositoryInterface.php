<?php

namespace Mistery23\LaravelRoles\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface UserRepositoryInterface
 */
interface UserRepositoryInterface
{

    /**
     * @param Model $user
     */
    public function add(Model $user): void;

    /**
     * @param Model $user
     */
    public function update(Model $user): void;

    /**
     * @param Model $user
     */
    public function remove(Model $user): void;
}
