<?php

use Codeable\Tests\Fixtures\User;


it('deletes any expired codes from the database', function () {
	$user = User::create();
	$user->createCode(timeToExpire: '-2:d');
	$this->assertDatabaseCount('codes', 1);
	$this->artisan('codes:delete-expired')->assertExitCode(0);
	$this->assertDatabaseCount('codes', 0);
});

it('cant deletes any not expired codes from the database', function () {
	$user = User::create();
	$user->createCode();
	$this->assertDatabaseCount('codes', 1);
	$this->artisan('codes:delete-expired')->assertExitCode(0);
	$this->assertDatabaseCount('codes', 1);
});
