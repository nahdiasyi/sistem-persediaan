<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'barang';

    // Primary key
    protected $primaryKey = 'kode_barang';
    public $incrementing = false; // karena tipe VARCHAR
    protected $keyType = 'string'; // pastikan Laravel tahu ini bukan integer

    public $timestamps = false;

    // Kolom yang bisa diisi
    protected $fillable = [
        'kode_barang',
        'id_kategori',
        'nama_barang',
        'harga_beli',
        'harga_jual',
        'Satuan',
        'merek',
        'stok',
        'keterangan'
    ];

    // Relasi ke tabel kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}
