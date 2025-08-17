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
        Schema::create('dosen', function (Blueprint $table) {
            $table->uuid('id_dosen');
            $table->string('nama_dosen');
            $table->char('jenis_kelamin', 1);
            $table->string('nidn')->nullable();
            $table->string('nuptk')->nullable();
            $table->string('nip')->nullable();
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama')->nullable();

            $table->index([
                'id_dosen',
                'nama_dosen',
                'nuptk',
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
