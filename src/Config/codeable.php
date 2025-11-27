<?php

declare(strict_types=1);

/**
 * Configuration file for code generation.
 *
 * This file defines constraints and rules used when generating unique codes
 * and managing their expiration.
 * Usage:
 * This configuration is returned as an associative array and can be imported
 * wherever code generation or expiration logic is required.
 */
return [
    /**
     *  min_length   : int
     *  The minimum number of digits allowed in a generated code.
     * */
    'min_length' => 3,

    /**
     *  max_length   : int
     *  The maximum number of digits allowed in a generated code.
     * */
    'max_length' => 16,

    /**
     *  max_attempts : int
     *  The maximum number of attempts to generate a unique code before failing.
     *  Example: 5 → after 5 unsuccessful tries, any code will be returned.
     * */
    'max_attempts' => 1,

    /**
     *   valid_units  : string[]
     *   Allowed time units for setting the `expire_at` field in the database record.
     */
    'valid_units' => [
        's' => 'second',
        'm' => 'minute',
        'h' => 'hour',
        'd' => 'day',
    ],
];
