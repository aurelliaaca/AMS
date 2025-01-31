<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function user(Request $request)
    {
        $user = Auth::user();

        // Mengambil data akun berdasarkan email
        $users = User::where('email', $user->email)->first();
        
        // Mengconvert role dari string nomor ke text
        $roleText = '';
        switch ($users->role) {
            case '1':
                $roleText = 'Super admin';
                break;
            case '2':
                $roleText = 'Admin';
                break;
            case '3':
                $roleText = 'Guest';
                break;
            default:
                $roleText = 'Unknown role';
                break;
        }
        return view('menu.dashboard', compact('users', 'roleText'));
    }
}