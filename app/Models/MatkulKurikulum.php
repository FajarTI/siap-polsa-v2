<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MatkulKurikulum extends Model
{
    use HasUuids;
    protected $table = 'matkul_kurikulum';
    protected $primaryKey = 'id_kurikulum';
    protected $fillable = [
        'id_kurikulum', 
        'id_matkul', 
        'wajib',
    ];

    public function matkul() {
        return $this->hasMany(MataKuliah::class, 'id_matkul', 'id_matkul');
    }
}
