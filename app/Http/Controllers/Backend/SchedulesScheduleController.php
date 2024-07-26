<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SchedulesSchedule;
use Illuminate\Http\Request;

class SchedulesScheduleController extends Controller
{
    public function index(){
        $scheduleData = SchedulesSchedule::all();
        return view('schedules.schedule.index', compact('scheduleData'));
    }
}
