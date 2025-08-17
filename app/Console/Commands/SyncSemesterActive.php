<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SemesterSyncService;

class SyncSemesterActive extends Command
{
    protected $signature = 'semester:sync';
    protected $description = 'Sinkron dosen dengan id_status_aktif = 1';

    public function handle(SemesterSyncService $svc): int
    {
        $n = $svc->syncActiveLecturers();
        $this->info("Synced {$n} semester aktif.");
        return self::SUCCESS;
    }
}
