<?php

use Codeable\Facades\Coder;

it('it can generate code', function () {
    $code = Coder::createCode();
    $this->assertDatabaseCount('codes',1);
    expect(Coder::codes()->count())->toBe(1);
});

it('it can retrive code by type', function () {
    $code = Coder::createCode('test');

    expect(Coder::codes()->count())->toBe(1)
    ->and(Coder::code($code->code))->not->toBeNull()
    ->and(Coder::code($code->code)->type)->toBe($code->type);
});

it('it can retrive code by id', function () {
    $code = Coder::createCode('test');

    expect(Coder::codes()->count())->toBe(1)
    ->and(Coder::codeById($code->id))->not->toBeNull()
    ->and(Coder::codeById($code->id)->type)->toBe($code->type);
});

it('it can delete code', function () {
    $code = Coder::createCode('test');

    expect(Coder::codes()->count())->toBe(1)
    ->and(Coder::codeById($code->id))->not->toBeNull();

    Coder::deleteCode($code->type);

    expect(Coder::codes()->count())->toBe(0)
    ->and(fn()=>Coder::code($code->type))->toThrow(\Exception::class)
    ->and(fn()=>Coder::codeById($code->id))->toThrow(\Exception::class);
});
