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
        Schema::create('matkul_kurikulum', function (Blueprint $table) {
            $table->uuid('id_kurikulum');
            $table->uuid('id_matkul');
            $table->enum('wajib', ['1', '2']); // 1: Wajib, 2:Tidak Wajib
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matkul_kurikulum');
    }
};
