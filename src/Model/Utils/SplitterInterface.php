<?php

namespace Mistery23\LaravelRoles\Model\Utils;

/**
 * Interface SplitterInterface
 */
interface SplitterInterface
{


    /**
     * Get an array from argument.
     *
     * @param integer|string|array $argument
     *
     * @return mixed
     */
    public function getArrayFrom($argument);
}
