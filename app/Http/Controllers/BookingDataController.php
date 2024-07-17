<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingDataController extends Controller
{
    public function index(){
        return view('booking.data.index');
    }
}
