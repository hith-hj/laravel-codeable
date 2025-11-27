<?php

declare(strict_types=1);

namespace Codeable\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class DeleteExpired extends Command
{
    protected $signature = 'codes:delete-expired';

    protected $description = 'Delete expired codes from the database';

    public function handle(): int
    {
        $deleted = DB::table('codes')
            ->whereNotNull('expire_at')
            ->where('expire_at', '<', now())
            ->delete();

        $this->info("Deleted {$deleted} expired codes.");

        return Command::SUCCESS;
    }
}
