<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterPort;
use Illuminate\Http\Request;

class PortApiController extends Controller
{
    public function index()
    {
        $data = MasterPort::all();
  
        return response()->json([
              'data' => $data,
        ], 200);
    }

    public function show_en($prt_slug_en)
    {
        // Mencari post berdasarkan slug
        $data = MasterPort::where('prt_slug_en', $prt_slug_en)->first();

        // Jika post tidak ditemukan, return 404
        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        // Jika ditemukan, return data post
        return response()->json($data, 200);
    }

    public function show_idn($prt_slug_idn)
    {
        // Mencari post berdasarkan slug
        $data = MasterPort::where('prt_slug_idn', $prt_slug_idn)->first();

        // Jika post tidak ditemukan, return 404
        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Jika ditemukan, return data post
        return response()->json($data, 200);
    }

}
