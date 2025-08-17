<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AgamaSyncService;

class SyncAgama extends Command
{
    protected $signature = 'agama:sync';
    protected $description = 'Sinkron Data Agama';

    public function handle(AgamaSyncService $svc): int
    {
        $n = $svc->syncActiveLecturers();
        $this->info("Synced {$n} Data Master Agama.");
        return self::SUCCESS;
    }
}
