<?php

namespace App\Http\Controllers;

use App\Models\ListJaringan;
use Illuminate\Http\Request;

class JaringanController extends Controller
{
    public function getDetail($id_jaringan)
    {
        $jaringan = ListJaringan::find($id_jaringan);

        if ($jaringan) {
            return response()->json([
                'success' => true,
                'data' => $jaringan
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data jaringan tidak ditemukan'
        ], 404);
    }
}
