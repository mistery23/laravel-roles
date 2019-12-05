<?php

namespace jeremykenedy\LaravelRoles\Contracts;

interface UserRepositoryInterface
{

    public function add($user): void;

    public function update($user): void;

    public function remove($user): void;
}
