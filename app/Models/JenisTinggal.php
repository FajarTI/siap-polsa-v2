<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisTinggal extends Model
{
    protected $table = 'jenis_tinggal';
    protected $primaryKey = 'id_jenis_tinggal';
    protected $fillable = [
        'id_jenis_tinggal',
        'nama_jenis_tinggal'
    ];
}
