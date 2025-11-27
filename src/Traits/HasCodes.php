<?php

declare(strict_types=1);

namespace Codeable\Traits;

use Codeable\Models\Code;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

trait HasCodes
{
    /**
     * Relation or base query for codes.
     */
    public function codes(): MorphMany|Builder
    {
        if ($this instanceof Model) {
            return $this->morphMany(Code::class, 'codeable');
        }

        return Code::query();
    }

    /**
     * Get code by id or type, or throw if not found.
     *
     * @throws RuntimeException
     */
    public function code(string|int $key): Code
    {
        if (is_numeric($key)) {
            return $this->codeById((int) $key);
        }

        return $this->codeByType((string) $key);
    }

    /**
     * Get code by id or throw if not found.
     *
     * @throws RuntimeException
     */
    public function codeById(int $id): Code
    {
        $code = $this->codes()->find($id);

        Truthy($code === null, "Code[{$id}] not found.");

        return $code;
    }

    /**
     * Get code by type or throw if not found.
     *
     * @throws RuntimeException
     */
    public function codeByType(string $type): Code
    {
        $code = $this->codes()->where('type', $type)->first();

        Truthy($code === null, "Code[{$type}] not found.");

        return $code;
    }

    /**
     * Get code by code or throw if not found.
     *
     * @throws RuntimeException
     */
    public function codeByCode(string $value): Code
    {
        $code = $this->codes()->where('code', $value)->first();

        Truthy($code === null, "Code[{$value}] not found.");

        return $code;
    }

    /**
     * Create or update a code (for model or globally if not used on a model).
     *
     * - If a record for the given type exists it will be updated.
     * - Otherwise it will be created.
     */
    public function createCode(
        string $type = 'test',
        int $length = 5,
        ?string $timeToExpire = '15:m'
    ): Code {
        return $this->setQuery()->updateOrCreate(
            ['type' => $type],
            [
                'code' => $this->generate($type, $length),
                'expire_at' => $this->expireAt($timeToExpire),
            ]
        );
    }

    /**
     * Delete a code by id, type or model instance.
     *
     * @param  int|string|Code  $param
     */
    public function deleteCode($param): bool|int
    {
        if ($param instanceof Code) {
            return $param->delete();
        }

        $query = $this->codes()
            ->where('type', $param)
            ->orWhere('id', $param);

        if ($query->exists()) {
            return $query->delete();
        }

        return false;
    }

    /**
     * Generate a unique code (fixed length).
     *
     * @param  string  $type  The code type/category.
     * @param  int  $length  The desired length of the code.
     * @return string The generated unique code.
     *
     * @throws RuntimeException If a unique code cannot be generated within max attempts.
     */
    private function generate(string $type, int $length)
    {
        $maxAttempts = (int) $this->getConfig('max_attempts', 5);

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $code = $this->generateNumber($length);

            if (
                ! $this->codes()
                    ->where([['type', $type], ['code', $code]])
                    ->exists()
            ) {
                return $code;
            }
        }
        Truthy(true, "Max attempts ({$maxAttempts}) reached.");
    }

    /**
     * Generate an integer with $length digits.
     */
    private function generateNumber(int $length = 5): int
    {
        $this->validateLength($length);

        $min = (int) pow(10, $length - 1);
        $max = (int) (pow(10, $length) - 1);

        return random_int($min, $max);
    }

    /**
     * Convert a pattern like "15:m" or "-5:h" to a Carbon instance.
     * Accepts signed integers. Null returns now().
     *
     * @throws InvalidArgumentException
     */
    private function expireAt(?string $timeToExpire): Carbon
    {
        if ($timeToExpire === null) {
            return now();
        }

        [$value, $unit] = $this->parseExpireAt($timeToExpire);

        $units = $this->getConfig('valid_units', []);

        if (! in_array($unit, array_keys($units), true) || $value === 0) {
            return now();
        }

        return now()->add((string) $units[$unit], (int) $value);
    }

    /**
     * Parse and Validate pattern like "-15:m" or "10:h".
     */
    private function parseExpireAt(string $input)
    {
        Truthy(
            ! preg_match('/^([+-]?\d+):(s|m|h|d)$/', trim($input), $matches),
            'Invalid time pattern.'
        );

        return [$matches[1], $matches[2]];
    }

    /**
     * Validate length bounds.
     *
     * @throws InvalidArgumentException
     */
    private function validateLength(int $length): void
    {
        $min = $this->getConfig('min_length', 3);
        $max = $this->getConfig('max_length', 16);
        Truthy($length < $min, "Min code length is {$min}");

        Truthy($length > $max, "Max code length is {$max}");
    }

    private function getConfig(string $key, $default = null): mixed
    {
        return config("codeable.{$key}", $default);
    }

    private function setQuery()
    {
        return $this->codes() instanceof Builder ?
            $this->codes()->withAttributes([
                'codeable_id' => $this->generateNumber(),
                'codeable_type' => $this::class,
            ]) :
            $this->codes();
    }
}
