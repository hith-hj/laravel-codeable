<?php

use Codeable\Tests\Fixtures\User;

beforeEach(function(){
    $this->user = User::create();
});

it('it can generate code', function () {
    $code = $this->user->createCode();
    $this->assertDatabaseCount('codes',1);
    expect($this->user->codes()->count())->toBe(1);
});

it('it can update code with same type', function () {
    $code = $this->user->createCode();
    $this->assertDatabaseCount('codes',1);
    expect($this->user->codes()->count())->toBe(1);
    $newCode = $this->user->createCode();
    $this->assertDatabaseCount('codes',1);
    expect($this->user->codes()->count())->toBe(1);
    expect($code->code)->not->toBe($newCode->code);
});

it('it generate new code with diff type', function () {
    $code = $this->user->createCode();
    $this->assertDatabaseCount('codes',1);
    expect($this->user->codes()->count())->toBe(1);
    $newCode = $this->user->createCode('tester');
    $this->assertDatabaseCount('codes',2);
    expect($this->user->codes()->count())->toBe(2);
});

it('it can retrive code by type', function () {
    $code = $this->user->createCode('test');

    expect($this->user->codes()->count())->toBe(1)
    ->and($this->user->code($code->type))->not->toBeNull()
    ->and($this->user->code($code->type)->type)->toBe($code->type);
});

it('it can retrive code by id', function () {
    $code = $this->user->createCode('test');

    expect($this->user->codes()->count())->toBe(1)
    ->and($this->user->codeById($code->id))->not->toBeNull()
    ->and($this->user->codeById($code->id)->type)->toBe($code->type);
});

it('it can delete code', function () {
    $code = $this->user->createCode('test');

    expect($this->user->codes()->count())->toBe(1)
    ->and($this->user->codeById($code->id))->not->toBeNull();

    $this->user->deleteCode($code->type);

    expect($this->user->codes()->count())->toBe(0)
    ->and(fn()=>$this->user->code($code->type))->toThrow(\Exception::class)
    ->and(fn()=>$this->user->codeById($code->id))->toThrow(\Exception::class);
});
