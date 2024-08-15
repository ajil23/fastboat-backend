<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FastboatAvailability;
use Illuminate\Http\Request;

class FastboatAvailabilityController extends Controller
{
    // this function is for view all data from fastboat table
    public function index()
    {
        return view('fast-boat.availability.index');
    }
}
