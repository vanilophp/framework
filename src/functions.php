<?php


if (!function_exists('shorten')) { // Taken from konekt/concord

    /**
     * Returns the classname shortened (no namespace, base class name only, snake_case
     *
     * @param string $classname
     *
     * @return string
     */
    function shorten($classname)
    {
        return snake_case(class_basename($classname));
    }

}