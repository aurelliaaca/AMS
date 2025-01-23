<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Menggunakan View Composer untuk menyuntikkan data ke sidebar
        View::composer(
            'layouts.sidebar', // View yang membutuhkan data
            function ($view) {
                $user = Auth::user();

                // Ambil data user yang sedang login
                $users = User::where('email', $user->email)->first();

                // Mengconvert role dari string nomor ke teks
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

                // Mengirimkan data ke sidebar view
                $view->with('users', $users)->with('roleText', $roleText);
            }
        );
    }

    public function register()
    {
        //
    }
}
