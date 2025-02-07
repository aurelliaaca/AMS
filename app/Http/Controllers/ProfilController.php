<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function getProfil()
    {
        $user = Auth::user(); // Mengambil data user yang sedang login
        return view('akun.profil', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diupdate');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah');
    }

    public function update(Request $request)
    {
        try {
            $user = auth()->user();
            
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'mobile_number' => 'required|string|max:15',
                'company' => 'nullable|string|max:255',
            ]);

            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
                'company' => $request->company,
            ]);

            // Refresh user data
            $user = $user->fresh();

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'user' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'mobile_number' => $user->mobile_number,
                    'company' => $user->company,
                    'email' => $user->email,
                    'role' => $user->role
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
            ], 500);
        }
    }
} 