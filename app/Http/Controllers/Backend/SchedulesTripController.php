<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SchedulesTrip;
use Illuminate\Http\Request;

class SchedulesTripController extends Controller
{
    // this function is for view all data from trip table
    public function index(){
        return view('schedules.trip.index');
    }

    public function add() 
    {
        return view('schedules.trip.add');   
    }
}
