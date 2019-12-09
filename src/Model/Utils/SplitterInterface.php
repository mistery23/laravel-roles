<?php

namespace jeremykenedy\LaravelRoles\Model\Utils;


/**
 * Interface SplitterInterface
 */
interface SplitterInterface
{

    /**
     * Get an array from argument.
     *
     * @param int|string|array $argument
     *
     * @return array
     */
    public function getArrayFrom($argument);
}
