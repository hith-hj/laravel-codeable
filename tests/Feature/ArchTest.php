<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;

arch()
    ->expect('Codable')
    ->toUseStrictTypes()
    ->toBeClasses()
    ->not->toUse(['die', 'dd', 'dump']);

arch()
    ->expect('Codable\Model')
    ->toExtend(Model::class);

arch()
    ->expect('Codable\Traits')
    ->toBeTraits();

arch()->preset()->security();
arch()->preset()->php();
