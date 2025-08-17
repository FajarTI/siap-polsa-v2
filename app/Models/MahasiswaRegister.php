<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MahasiswaRegister extends Model
{
    use HasUuids;
    protected $table = 'riwayat_pendidikan';
    protected $primaryKey = 'id_registrasi_mahasiswa';
    protected $fillable = [
        'id_registrasi_mahasiswa',
        'id_mahasiswa',
        'nim',
        'id_jenis_daftar',
        'id_jalur_daftar',
        'id_perguruan_tingi',
        'id_prodi',
        'id_periode_masuk',
        'id_prodi_asal',
        'id_perguruan_tinggi_asal',
        'id_pembiayaan',
        'tanggal_daftar',
        'sks_diakui,'
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    public function biodata()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function programStudi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi', 'id_prodi');
    }
}
