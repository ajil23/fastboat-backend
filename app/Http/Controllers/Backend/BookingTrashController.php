<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingTrashController extends Controller
{
    public function index()
    {
        return view('booking.trash.index');    
    }
}
