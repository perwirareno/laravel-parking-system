<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parkir extends Model
{
    protected $table = 'master_parkir';

    protected $fillable = [
        'no_polisi','kode_unik','inorout','jam_masuk','jam_keluar','biaya'
    ];
}
