<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mutasi extends Model
{
    protected $table='mutasi';
    protected $fillable=["id_order","jumlah_mutasi","tgl_mutasi", "status_mutasi"];
    
}
