<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_pendidikan', function (Blueprint $table) {
            $table->uuid('id_registrasi_mahasiswa');
            $table->string('id_mahasiswa');
            $table->string('nim')->unique();
            $table->string('id_jenis_daftar')->nullable();
            $table->string('id_jalur_daftar')->nullable();
            $table->string('id_periode_masuk')->nullable();
            $table->date('tanggal_daftar')->nullable();
            $table->string('id_perguruan_tinggi')->nullable();
            $table->string('id_prodi')->nullable();
            $table->string('sks_diakui')->nullable();
            $table->string('id_perguruan_tinggi_asal')->nullable();
            $table->string('id_prodi_asal')->nullable();
            $table->string('id_pembiayaan')->nullable();

            $table->index([
                'id_registrasi_mahasiswa',
                'id_prodi',
                'nim'
            ], 'riwayat_pendidikan');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pendidikan');
    }
};
