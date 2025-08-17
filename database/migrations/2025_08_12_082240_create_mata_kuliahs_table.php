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
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->uuid('id_matkul');
            $table->string('id_prodi');
            $table->string('kode_mata_kuliah');
            $table->string('nama_mata_kuliah');
            $table->char('id_jenis_matkul', 1)->nullable();
            $table->string('id_kelompok_matkul')->nullable();
            $table->string('sks_matkul')->nullable();
            $table->string('sks_tatap_muka')->nullable();
            $table->string('sks_praktek')->nullable();
            $table->string('sks_praktek_lapangan')->nullable();
            $table->string('sks_simulasi')->nullable();
            $table->string('metode_kuliah')->nullable();
            $table->date('tanggal_mulai_efektif')->nullable();
            $table->date('tanggal_selesai_efektif')->nullable();

            $table->index([
                'id_matkul',
                'id_prodi',
                'kode_mata_kuliah',
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};
