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
        Schema::create('kurikulum', function (Blueprint $table) {
            $table->uuid('id_kurikulum');
            $table->string('nama_kurikulum');
            $table->string('id_prodi');
            $table->string('id_semester');
            $table->string('jumlah_sks_lulus');
            $table->string('jumlah_sks_wajib');
            $table->string('jumlah_sks_pilihan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurikulum');
    }
};
