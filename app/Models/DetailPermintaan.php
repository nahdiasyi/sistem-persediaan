<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPermintaan extends Model
{
    protected $table = 'detail_permintaan';
    public $timestamps = false;

    protected $fillable = [
        'Id_Permintaan',
        'Id_barang',
        'Jumlah_Diminta',
        'Jumlah_Disetujui'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'Id_barang', 'Id_Barang');
    }
}