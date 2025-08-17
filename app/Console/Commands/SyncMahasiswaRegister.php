<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MahasiswaRegisterSyncService;

class SyncMahasiswaRegister extends Command
{
    protected $signature = 'mahasiswa-register:sync';
    protected $description = 'Sinkron mahasiswa';

    public function handle(MahasiswaRegisterSyncService $svc): int
    {
        $n = $svc->syncActiveLecturers();
        $this->info("Synced {$n} mahasiswa berhasil!.");
        return self::SUCCESS;
    }
}
