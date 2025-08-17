<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MahasiswaSyncService;

class SyncMahasiswa extends Command
{
    protected $signature = 'mahasiswa:sync';
    protected $description = 'Sinkron mahasiswa';

    public function handle(MahasiswaSyncService $svc): int
    {
        $n = $svc->syncActiveLecturers();
        $this->info("Synced {$n} mahasiswa berhasil!.");
        return self::SUCCESS;
    }
}
