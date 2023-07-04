<?php

if (!function_exists('dd')) {

    function dd(mixed ...$value)
    {
        echo '<pre>'.print_r($value, true);
        die;
    }
}

if (!function_exists('dd_if')) {

    function dd_if(callable $condition, mixed ...$value): void
    {
        if ($condition()) {
            dd($value);
        }
    }
}

if (!function_exists('is_false')) {

    function is_false(mixed $value): bool
    {
        return $value === false;
    }
}