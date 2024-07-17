<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingTrashController extends Controller
{
    public function index(){
        return view('booking.trash.index');
    }
}
