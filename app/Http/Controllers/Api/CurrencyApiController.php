<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterCurrency;
use Illuminate\Http\Request;

class CurrencyApiController extends Controller
{
    public function index()
    {
        $data = MasterCurrency::all();

        return response()->json([
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ], 200);
    }
}
