<?php

use Codeable\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()
	->extend(TestCase::class)
	->use(RefreshDatabase::class)
	->in('Feature', 'Unit');
