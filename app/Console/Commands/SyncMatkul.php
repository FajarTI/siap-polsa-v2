<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MataKuliahSyncService;

class SyncMatkul extends Command
{
    protected $signature = 'matkul:sync';
    protected $description = 'Sinkron mata kuliah';

    public function handle(MataKuliahSyncService $svc): int
    {
        $n = $svc->syncActiveLecturers();
        $this->info("Synced {$n} Mata Kuliah!.");
        return self::SUCCESS;
    }
}
