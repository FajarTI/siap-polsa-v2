<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasUuids;
    protected $table = 'semester';
    protected $primaryKey = 'id_semester';
    protected $fillable = [
        'id_semester',
        'id_tahun_ajaran',
        'nama_semester',
        'semester',
        'a_periode_aktif'
    ];
}
