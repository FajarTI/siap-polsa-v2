<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agama extends Model
{
    protected $table = 'agama';
    protected $fillable = [
        'id_agama',
        'nama_agama'
    ];
    protected $primaryKey = 'id_agama';
}
