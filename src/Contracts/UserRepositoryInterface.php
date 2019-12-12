<?php

namespace Mistery23\LaravelRoles\Contracts;

use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{

    public function add(Model $user): void;

    public function update(Model $user): void;

    public function remove(Model $user): void;
}
