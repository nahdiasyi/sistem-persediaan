<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user'; // nama tabel sesuai database

    protected $primaryKey = 'id_user'; // primary key

    public $incrementing = false; // karena varchar, bukan auto increment

    protected $keyType = 'string'; // primary key string

    public $timestamps = false; // **JANGAN BIARKAN LARAVEL OTOMATIS ISI created_at & updated_at**

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
