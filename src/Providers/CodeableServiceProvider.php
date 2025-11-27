<?php

declare(strict_types=1);

namespace Codeable\Providers;

use Codeable\Codeable;
use Codeable\Commands\DeleteExpired;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

final class CodeableServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Codeable::class, function ($app) {
            return new Codeable();
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../Commands/DeleteExpired.php' => app_path('Commands/DeleteExpired'),
        ], 'codeable-command');

        $this->publishes([
            __DIR__.'/../Config/codeable.php' => config_path('codeable.php'),
        ], 'codeable-config');

        $this->publishes([
            __DIR__.'/../Commands/DeleteExpired.php' => app_path('Commands/DeleteExpired'),
            __DIR__.'/../Config/codeable.php' => config_path('codeable.php'),
            __DIR__.'/../Database/migrations/create_codes_table.php' => $this->getMigrationFileName('create_codes_table.php'),
        ], 'codeable-files');

        $this->publishesMigrations([
            __DIR__.'/../Database/migrations/create_codes_table.php' => $this->getMigrationFileName('create_codes_table.php'),
        ], 'codeable-migrations');

        $this->commands([DeleteExpired::class]);
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../Config/codeable.php', 'codeable');
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     */
    private function getMigrationFileName(string $migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make([$this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR])
            ->flatMap(fn ($path) => $filesystem->glob($path.'*_'.$migrationFileName))
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}
