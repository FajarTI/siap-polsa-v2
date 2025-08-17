<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasUuids;
    protected $table = 'mata_kuliah';
    protected $primaryKey = 'id_matkul';

    protected $fillable = [
        'id_matkul',
        'id_prodi',
        'kode_mata_kuliah',
        'nama_mata_kuliah',
        'id_jenis_matkul',
        'id_kelompok_matkul',
        'sks_matkul',
        'sks_tatap_muka',
        'sks_praktek',
        'sks_praktek_lapangan',
        'sks_simulasi',
        'metode_kuliah',
        'tanggal_mulai_efektif',
        'tanggal_selesai_efektif',
    ];

    protected $casts = [
        'tanggal_mulai_efektif' => 'date',
        'tanggal_selesai_efektif' => 'date',
    ];
}
