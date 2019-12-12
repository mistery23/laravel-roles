<?php


namespace Mistery23\LaravelRoles\Model\Utils;


/**
 * Class Splitter
 */
class Splitter implements SplitterInterface
{

    /**
     * Get an array from argument.
     *
     * @param int|string|array $argument
     *
     * @return array
     */
    public function getArrayFrom($argument)
    {
        return (!is_array($argument)) ? preg_split('/ ?[,|] ?/', $argument) : $argument;
    }
}
