<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    protected $primaryKey = 'id_wilayah';
    protected $fillable = [
        'id_wilayah',
        'id_level_wilayah',
        'id_negara',
        'nama_wilayah',
        'id_induk_wilayah',
    ];
}
