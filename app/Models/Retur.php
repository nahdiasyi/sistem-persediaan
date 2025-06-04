<?php
// File: app/Models/Retur.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    protected $table = 'retur';
    protected $primaryKey = 'id_retur';
    public $incrementing = false;
    public $timestamps = false;

    
    protected $fillable = ['id_retur', 'id_pembelian', 'id_supplier', 'id_user', 'tanggal', 'status'];


    public function detailRetur()
    {
        return $this->hasMany(DetailRetur::class, 'id_retur', 'id_retur');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian', 'id_pembelian');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}