<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Dosen extends Model
{
    use HasUuids;
    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';
    protected $fillable = [
        'id_dosen',
        'nama_dosen',
        'email',
        'no_hp',
        'agama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'nip',
        'nuptk',
        'nidn',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
}
