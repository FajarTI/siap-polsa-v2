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
        Schema::create('semester', function (Blueprint $table) {
            $table->uuid('id_semester');
            $table->string('id_tahun_ajaran');
            $table->string('nama_semester');
            $table->string('semester');
            $table->string('a_periode_aktif');

            $table->index([
                'id_semester',
                'nama_semester'
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester');
    }
};
