<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasUuids;
    protected $table = 'prodi';
    protected $primaryKey = 'id_prodi';
    protected $fillable = [
        'id_prodi',
        'kode_program_studi',
        'nama_program_studi',
        'status',
        'nama_jenjang_pendidikan',
    ];
}
