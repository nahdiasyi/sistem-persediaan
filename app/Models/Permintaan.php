<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    use HasFactory;

    protected $table = 'permintaan';
    protected $primaryKey = 'id_permintaan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_permintaan',
        'id_supplier',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relasi ke supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }

    // Relasi ke detail permintaan
    public function detailPermintaan()
    {
        return $this->hasMany(DetailPermintaan::class, 'id_permintaan', 'id_permintaan');
    }

    // Generate ID Permintaan otomatis
    public static function generateId()
    {
        $lastPermintaan = self::orderBy('id_permintaan', 'desc')->first();

        if (!$lastPermintaan) {
            return 'PRM001';
        }

        $lastNumber = intval(substr($lastPermintaan->id_permintaan, 3));
        $newNumber = $lastNumber + 1;

        return 'PRM' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
