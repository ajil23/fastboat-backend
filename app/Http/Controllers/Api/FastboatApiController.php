<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataFastboat;
use Illuminate\Http\Request;

class FastboatApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DataFastboat::all();

        return response()->json([
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource (fb_slug_en).
     */
    public function show_en($fb_slug_en)
    {
        // Mencari post berdasarkan slug
        $data = DataFastboat::where('fb_slug_en', $fb_slug_en)->first();

        // Jika post tidak ditemukan, return 404
        if (!$data) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Jika ditemukan, return data post
        return response()->json($data, 200);
    }

      /**
     * Display the specified resource (fb_slug_idn).
     */
    public function show_idn($fb_slug_idn)
    {
        // Mencari post berdasarkan slug
        $data = DataFastboat::where('fb_slug_idn', $fb_slug_idn)->first();

        // Jika post tidak ditemukan, return 404
        if (!$data) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Jika ditemukan, return data post
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
