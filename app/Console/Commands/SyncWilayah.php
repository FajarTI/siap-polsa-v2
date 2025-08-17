<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WilayahSyncService;

class SyncWilayah extends Command
{
    protected $signature = 'wilayah:sync';
    protected $description = 'Sinkron Data Wilayah';

    public function handle(WilayahSyncService $svc): int
    {
        $n = $svc->syncActiveLecturers();
        $this->info("Synced {$n} Data Master Wilayah.");
        return self::SUCCESS;
    }
}
