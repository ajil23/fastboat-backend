<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FastboatAvailability;
use Illuminate\Http\Request;

class AvailabilityApiController extends Controller
{
    public function index()
    {
        $data = FastboatAvailability::all();

        return response()->json([
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ], 200);
    }
}
