<?php

namespace App\Console\Commands;

use App\Services\JenjangPendidikanSyncService;
use Illuminate\Console\Command;

class SyncJenjangPendidikan extends Command
{
    protected $signature = 'pendidikan:sync';
    protected $description = 'Sinkron Data Jenjang Pendidikan';

    public function handle(JenjangPendidikanSyncService $svc): int
    {
        $n = $svc->syncActiveLecturers();
        $this->info("Synced {$n} Data Master Jenjang Pendidikan.");
        return self::SUCCESS;
    }
}
