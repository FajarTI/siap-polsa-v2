<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model
{
    use HasUuids;
    protected $table = 'kurikulum';
    protected $primaryKey = 'id_kurikulum';
    protected $fillable = [
        'id_kurikulum',
        'nama_kurikulum',
        'id_prodi',
        'id_semester',
        'jumlah_sks_lulus', // hasil jumlah sks_wajib + sks_pilihan
        'jumlah_sks_wajib',
        'jumlah_sks_pilihan',
    ];

    public function matkulKurikulum() {
        return $this->hasMany(MatkulKurikulum::class, 'id_kurikulum');
    }
}

