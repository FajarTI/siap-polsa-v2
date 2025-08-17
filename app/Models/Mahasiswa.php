<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasUuids;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    protected $fillable = [
        'id_mahasiswa',
        'nama_mahasiswa',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'id_agama',
        'nik',
        'kewarganegaraan',
        'kelurahan',
        'id_wilayah',
        'penerima_kps',
        'nama_ibu_kandung',
        'id_kebutuhan_khusus_mahasiswa',
        'id_kebutuhan_khusus_ayah',
        'id_kebutuhan_khusus_ibu',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function riwayatPendidikan()
    {
        return $this->hasOne(MahasiswaRegister::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function agama()
    {
        return $this->belongsTo(Agama::class, 'id_agama', 'id_agama');
    }
}
