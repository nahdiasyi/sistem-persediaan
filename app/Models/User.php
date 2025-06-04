<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user'; // sesuai dengan nama tabel kamu

    protected $primaryKey = 'id_user'; // karena bukan 'id'

    public $incrementing = false; // karena primary key varchar

    protected $keyType = 'string'; // karena id_user varchar

    protected $fillable = [
        'id_user',
        'nama_user',
        'alamat',
        'no_telp',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];
}

