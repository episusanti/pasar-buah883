<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    protected $table='orders';
    protected $fillable=["kode","no_order","jumlah", "status_order","tgl_order","user_id"];
    
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function stokBarang()
{
    return $this->belongsTo(StokBarang::class, 'kode');
}
}
