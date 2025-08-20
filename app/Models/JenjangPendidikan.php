<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenjangPendidikan extends Model
{
    protected $table = 'jenjang_pendidikan';
    protected $primaryKey = 'id_jenjang_didik';
    protected $fillable = [
        'id_jenjang_didik',
        'nama_jenjang_didik'
    ];
}
