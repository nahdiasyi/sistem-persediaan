<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPermintaan extends Model
{
    use HasFactory;

    protected $table = 'detail_permintaan';
    public $timestamps = false;

    protected $fillable = [
        'id_permintaan',
        'kode_barang',
        'jumlah',
    ];

    // Relasi ke permintaan
    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class, 'id_permintaan', 'id_permintaan');
    }

    // Relasi ke barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }
}
