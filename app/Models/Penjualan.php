<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    // Nama tabel dan primary key
    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';

    // Karena id-nya bukan auto increment
    public $incrementing = false;

    // Nonaktifkan timestamps default Laravel (created_at, updated_at)
    public $timestamps = false;

    // Field yang bisa diisi massal
    protected $fillable = ['id_penjualan', 'id_user', 'tanggal'];

    // ✅ Tambahkan casting agar kolom `tanggal` otomatis menjadi objek Carbon
    protected $casts = [
        'tanggal' => 'datetime',
    ];

    // Relasi ke tabel user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke detail penjualan
    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan');
    }
}
