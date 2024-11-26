<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataRoute;
use Illuminate\Http\Request;

class RouteApiController extends Controller
{
    public function index()
    {
        $data = DataRoute::all();

        return response()->json([
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ], 200);
    }
}
