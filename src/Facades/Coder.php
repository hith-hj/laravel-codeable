<?php

declare(strict_types=1);

namespace Codeable\Facades;

use Codeable\Codeable;
use Illuminate\Support\Facades\Facade;

final class Coder extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Codeable::class;
    }
}
