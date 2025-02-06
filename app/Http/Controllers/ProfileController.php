<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
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