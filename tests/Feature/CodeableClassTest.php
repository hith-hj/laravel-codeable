<?php

use Codeable\Codeable;

beforeEach(function(){
    $this->codable = new Codeable();
});

it('it can generate code', function () {
    $code = $this->codable->createCode();
    $this->assertDatabaseCount('codes',1);
    expect($this->codable->codes()->count())->toBe(1);
});

it('it can\'t update generated code', function () {
    $code = $this->codable->createCode();
    $this->assertDatabaseCount('codes',1);
    expect($this->codable->codes()->count())->toBe(1);
    $second = $this->codable->createCode();
    $this->assertDatabaseCount('codes',2);
    expect($this->codable->codes()->count())->toBe(2);
});

it('it can retrive code by type', function () {
    $code = $this->codable->createCode('test');

    expect($this->codable->codes()->count())->toBe(1)
    ->and($this->codable->code($code->type))->not->toBeNull()
    ->and($this->codable->code($code->type)->type)->toBe($code->type);
});

it('it can retrive code by id', function () {
    $code = $this->codable->createCode('test');

    expect($this->codable->codes()->count())->toBe(1)
    ->and($this->codable->codeById($code->id))->not->toBeNull()
    ->and($this->codable->codeById($code->id)->type)->toBe($code->type);
});

it('it can delete code', function () {
    $code = $this->codable->createCode('test');

    expect($this->codable->codes()->count())->toBe(1)
    ->and($this->codable->codeById($code->id))->not->toBeNull();

    $this->codable->deleteCode($code->type);

    expect($this->codable->codes()->count())->toBe(0)
    ->and(fn()=>$this->codable->code($code->type))->toThrow(\Exception::class)
    ->and(fn()=>$this->codable->codeById($code->id))->toThrow(\Exception::class);
});
