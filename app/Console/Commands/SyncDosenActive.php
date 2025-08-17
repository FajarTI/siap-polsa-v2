<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DosenSyncService;

class SyncDosenActive extends Command
{
    protected $signature = 'dosen:sync';
    protected $description = 'Sinkron dosen dengan id_status_aktif = 1';

    public function handle(DosenSyncService $svc): int
    {
        $n = $svc->syncActiveLecturers();
        $this->info("Synced {$n} dosen aktif.");
        return self::SUCCESS;
    }
}
