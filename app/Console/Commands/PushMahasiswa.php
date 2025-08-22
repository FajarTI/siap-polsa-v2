<?php

namespace App\Console\Commands;

use App\Models\Mahasiswa;
use Illuminate\Console\Command;
use App\Services\MahasiswaSyncService;

class PushMahasiswa extends Command
{
    protected $signature = 'mahasiswa:push';
    protected $description = 'Sinkron mahasiswa';

    public function handle(MahasiswaSyncService $svc): int
    {

        $mahasiswas = Mahasiswa::latest()->get();

        foreach ($mahasiswas as $mahasiswa) {
            $svc->insertBiodataMahasiswa($mahasiswa);
        }

        $this->info("Berhasil push data ke Feeder!.");
        return self::SUCCESS;
    }
}
