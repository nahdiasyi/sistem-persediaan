<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user')->insert([
            [
                'id_user' => 'USR006',
                'nama_user' => 'Amel',
                'alamat' => 'Ngglik,Sleman',
                'no_telp' => '087865438724',
                'username' => 'amel',
                'password' => Hash::make('amel1'), // Menggunakan bcrypt untuk keamanan
                'role' => 'back office',
            ],
        ]);
    }
}
