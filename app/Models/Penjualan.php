<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id_penjualan', 'id_user', 'tanggal'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan');
    }
}
