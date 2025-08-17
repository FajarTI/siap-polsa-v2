<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProdiSyncService;

class SyncProdi extends Command
{
    protected $signature = 'prodi:sync';
    protected $description = 'Sinkron prodi';

    public function handle(ProdiSyncService $svc): int
    {
        $n = $svc->syncActiveLecturers();
        $this->info("Synced {$n} program studi.");
        return self::SUCCESS;
    }
}
