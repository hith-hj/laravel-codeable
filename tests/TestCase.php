<?php

declare(strict_types=1);

namespace Codeable\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Codeable\Providers\CodeableServiceProvider;
use Codeable\Tests\Fixtures\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            CodeableServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate')->run();

        if (! Schema::hasTable('codes')) {
            $this->fail('Migrations Faild');
        }

        $this->setDBTables();
    }

    private function setDBTables()
    {
        $schema = $this->app['db']->connection()->getSchemaBuilder();
        if (! $schema->hasTable('users')) {
            $schema->create('users', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }

        if (! $schema->hasTable('posts')) {
            $schema->create('posts', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(User::class);
                $table->timestamps();
            });
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
