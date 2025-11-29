<?php

declare(strict_types=1);

if (! function_exists('Truthy')) {
    /**
     * Throw an exception if the condition is true.
     *
     * @param  bool  $condition  The condition to evaluate.
     * @param  string  $message  The exception message if condition is true.
     * @param  mixed  ...$parameters  Optional parameters passed to the Exception constructor.
     * @return bool Returns the evaluated condition (always false if exception is thrown).
     *
     * @throws Exception
     */
    function Truthy(bool $condition, string $message, mixed ...$parameters): bool
    {
        if ($condition) {
            throw new Exception(__($message), ...$parameters);
        }

        return $condition;
    }
}

if (! function_exists('Falsy')) {
    /**
     * Throw an exception if the condition is false.
     *
     * @param  bool  $condition  The condition to evaluate.
     * @param  string  $message  The exception message if condition is true.
     * @param  mixed  ...$parameters  Optional parameters passed to the Exception constructor.
     * @return bool Returns the evaluated condition (always false if exception is thrown).
     *
     * @throws Exception
     */
    function Falsy(bool $condition, string $message, mixed ...$parameters): bool
    {
        return Truthy(! $condition, $message, ...$parameters);
    }
}
