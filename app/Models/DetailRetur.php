<?php
// File: app/Models/DetailRetur.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailRetur extends Model
{
    protected $table = 'detail_retur';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['id_retur', 'kode_barang', 'jumlah', 'harga', 'alasan'];

    public function retur()
    {
        return $this->belongsTo(Retur::class, 'id_retur', 'id_retur');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }
}