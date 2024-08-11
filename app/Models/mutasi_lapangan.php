<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mutasi_lapangan extends Model
{
    protected $table='mutasi_lapangan';
    protected $fillable=["kode","jumlah_mutasi","terima_mutasi","tgl_mutasi", "status_mutasi"];
}
