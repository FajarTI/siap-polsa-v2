<?php

namespace App\Console\Commands;

use App\Models\MahasiswaRegister;
use Illuminate\Console\Command;
use App\Services\MahasiswaSyncService;

class PushRiwayatPendidikanMahasiswa extends Command
{
    protected $signature = 'riwayat-pendidikan-mahasiswa:push';
    protected $description = 'Sinkron mahasiswa';

    public function handle(MahasiswaSyncService $svc): int
    {

        $mahasiswas = MahasiswaRegister::whereNot('id_periode_masuk', '')
        ->latest()
        ->get();

        // dd($mahasiswas);

        foreach ($mahasiswas as $mahasiswa) {
            $svc->insertRiwayatPendidikan($mahasiswa);
        }

        $this->info("Berhasil push data ke Feeder!.");
        return self::SUCCESS;
    }
}
