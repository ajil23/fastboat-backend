<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterNationality;
use Illuminate\Http\Request;

class NationalityApiController extends Controller
{
    public function index()
    {
        $data = MasterNationality::all();

        return response()->json([
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ], 200);
    }
}
