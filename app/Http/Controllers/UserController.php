<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    // Menampilkan form untuk menambah user baru
    public function create()
    {
        // Ambil user terakhir berdasarkan id_user
        $lastUser = DB::table('user')
            ->select('id_user')
            ->orderBy('id_user', 'desc')
            ->first();

        // Ambil angka dari id_user terakhir (contoh: USR009 → 9)
        $lastNumber = $lastUser ? (int) substr($lastUser->id_user, 3) : 0;

        // Tambah 1 dan buat format baru
        $newId = 'USR' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return view('user.create', compact('newId'));
    }


    // Menyimpan data user baru
    public function store(Request $request)
    {
        $request->validate([
            'id_user'    => 'required|string|max:20|unique:user,id_user',
            'nama_user'  => 'required|string|max:100',
            'alamat'     => 'required|string|max:40',
            'no_telp'    => 'required|string|max:13',
            'username'   => 'required|string|max:20|unique:user,username',
            'password'   => 'required|string|min:6|max:30',
            'role'       => 'required|in:back office,kasir,owner',
        ]);

        User::create([
            'id_user'    => $request->id_user,
            'nama_user'  => $request->nama_user,
            'alamat'     => $request->alamat,
            'no_telp'    => $request->no_telp,
            'username'   => $request->username,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    // Menampilkan form edit user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    // Memperbarui data user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'id_user'    => 'required|string|max:20',
            'nama_user'  => 'required|string|max:100',
            'alamat'     => 'required|string|max:40',
            'no_telp'    => 'required|string|max:13',
            'username'   => 'required|string|max:20|unique:user,username,' . $id . ',id_user',
            'role'       => 'required|in:back office,kasir,owner',
        ]);

        $user->id_user = $request->id_user;
        $user->nama_user = $request->nama_user;
        $user->alamat = $request->alamat;
        $user->no_telp = $request->no_telp;
        $user->username = $request->username;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
    }

    // Menghapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }
}
