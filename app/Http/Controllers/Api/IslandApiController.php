<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterIsland;
use Illuminate\Http\Request;

class IslandApiController extends Controller
{
    public function index()
    {
        $data = MasterIsland::all();

        return response()->json([
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ], 200);
    }
}
