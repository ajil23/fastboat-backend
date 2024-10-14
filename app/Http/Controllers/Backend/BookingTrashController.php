<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BookingData;
use Illuminate\Http\Request;

class BookingTrashController extends Controller
{
    public function index()
    {
        $bookingData = BookingData::orderBy('created_at', 'desc')->get();
        return view('booking.trash.index', compact('bookingData'));    
    }
}
