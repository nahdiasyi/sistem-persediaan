<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // dd(Auth::user());
        // dd(config('auth.guards.web'),
        //     config('auth.providers.admins')
        // );


        return view('dashboard');
    }
}
