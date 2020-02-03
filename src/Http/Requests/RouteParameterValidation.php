<?php

/**
 * PHP version 7.3
 *
 * @package Mistery23\LaravelRoles\App\Http\Requests
 * @author  Oleksandr Barabolia <alexandrbarabolya@gmail.com>
 */

declare(strict_types=1);

namespace Mistery23\LaravelRoles\App\Http\Requests;

/**
 * Trait RouteParameterValidation
 */
trait RouteParameterValidation
{


    /**
     * @param array|mixed|null $keys
     *
     * @return array
     */
    public function all($keys = null)
    {
        return array_merge(parent::all(), $this->route()->parameters());
    }
}
