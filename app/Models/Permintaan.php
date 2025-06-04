<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    protected $table = 'permintaan';
    protected $primaryKey = 'Id_Permintaan';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'Id_Permintaan',
        'Id_Admin_Toko',
        'Tanggal',
        'status'
    ];

    public function detailPermintaan()
    {
        return $this->hasMany(DetailPermintaan::class, 'Id_Permintaan', 'Id_Permintaan');
    }
}

