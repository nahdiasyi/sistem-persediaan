<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'kategori';

    // Primary key yang bukan auto-increment
    protected $primaryKey = 'id_kategori';
    public $incrementing = false;
    protected $keyType = 'string';

    // Tidak menggunakan timestamps (created_at, updated_at)
    public $timestamps = false;

    // Kolom-kolom yang dapat diisi
    protected $fillable = [
        'id_kategori',
        'nama_kategori',
    ];

    // Relasi jika ada, misal:
    // public function barang()
    // {
    //     return $this->hasMany(Barang::class, 'id_kategori', 'id_kategori');
    // }
}
