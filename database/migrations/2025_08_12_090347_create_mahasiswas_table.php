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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->uuid('id_mahasiswa');
            $table->string('nama_mahasiswa');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->string('tanggal_lahir');
            $table->smallInteger('id_agama');
            $table->string('nik');
            $table->string('kewarganegaraan');
            $table->string('kelurahan');
            $table->char('id_wilayah', 8);
            $table->integer('penerima_kps');
            $table->string('nama_ibu_kandung');
            $table->integer('id_kebutuhan_khusus_mahasiswa')->default(0);
            $table->integer('id_kebutuhan_khusus_ayah')->default(0);
            $table->integer('id_kebutuhan_khusus_ibu')->default(0);

            $table->index([
                'id_mahasiswa',
                'nama_mahasiswa'
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
