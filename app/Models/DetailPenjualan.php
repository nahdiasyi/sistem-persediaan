<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';
    public $timestamps = false;

    protected $fillable = [
        'id_penjualan',
        'kode_barang',
        'jumlah',
        'harga'
    ];

    public function barang()
    {
        // foreignKey = 'kode_barang' di detail_penjualan
        // ownerKey = 'kode_barang' di barang
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan');
    }
}
