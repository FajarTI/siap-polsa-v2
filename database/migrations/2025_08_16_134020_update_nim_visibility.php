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
        Schema::table('riwayat_pendidikan', function (Blueprint $table) {
            $table->string('nim')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_pendidikan', function (Blueprint $table) {
            $table->dropColumn('nim');
            $table->string('nim')->unique()->nullable()->after('id_mahasiswa');
        });
    }
};
