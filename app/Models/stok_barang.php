<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stok_barang extends Model
{
    protected $table='stok_barang';
    protected $fillable=["kode", "nama_stok", "barcode", "qty_lapangan", "qty_gudang_kecil", "qty_gudang_besar"];
}
