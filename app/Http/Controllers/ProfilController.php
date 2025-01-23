<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan ini ditambahkan untuk menggunakan Auth

class ProfilController extends Controller
{
    public function getProfil()
    {
        return view('akun.profil'); // Pastikan halaman login berada di resources/views/login.blade.php
    }
}
