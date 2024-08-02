<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SchedulesShuttle;
use App\Models\SchedulesShuttleArea;
use App\Models\SchedulesTrip;
use Illuminate\Http\Request;

class SchedulesShuttleController extends Controller
{
    // this function is for view all data from shuttlearea table
    public function index(){
        $shuttleData = SchedulesShuttle::with(['trip.schedule.company'])->get();
        $trip = SchedulesTrip::with(['departure.arrival']);
        $area = SchedulesShuttleArea::all();
        $title = 'Delete Shuttle Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('schedules.shuttle.index', compact('shuttleData', 'trip', 'area'));
    }

    public function add()
    {
        $trip = SchedulesTrip::all();
        $area = SchedulesShuttleArea::all();
        return view('schedules.shuttle.add', compact('trip', 'area'));
    }

    public function store(Request $request)
    {
        // Handle the request data validation
        $request->validate([
            's_trip' => 'required',
            's_area' => 'required',
            's_start' => 'required',
            's_end' => 'required',
            's_meeting_point' => 'required',
        ]);

        // Handle insert data to database
        $shuttleData = new SchedulesShuttle();
        $shuttleData->s_trip = $request->s_trip;
        $shuttleData->s_area = $request->s_area;
        $shuttleData->s_start = $request->s_start;
        $shuttleData->s_end = $request->s_end;
        $shuttleData->s_meeting_point = $request->s_meeting_point;
        $shuttleData->s_updated_by = Auth()->id();
        $shuttleData->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('shuttle.view');
    }

    public function edit($id)
    {
        $shuttleData = SchedulesShuttle::find($id);
        $trip = SchedulesTrip::all();
        $area = SchedulesShuttleArea::all();
        return view('schedules.shuttle.edit', compact('shuttleData', 'trip', 'area'));
    }

    public function update (Request $request, $id)
    {
        // Handle insert data to database
        $shuttleData = SchedulesShuttle::find($id);
        $shuttleData->s_trip = $request->s_trip;
        $shuttleData->s_area = $request->s_area;
        $shuttleData->s_start = $request->s_start;
        $shuttleData->s_end = $request->s_end;
        $shuttleData->s_meeting_point = $request->s_meeting_point;
        $shuttleData->s_updated_by = Auth()->id();
        $shuttleData->save();
        toast('Your data as been updated!', 'success');
        return redirect()->route('shuttle.view');
    }

    // this function will get the $id of selected data and do delete operation
    public function delete($id)
    {
        $shuttleData = SchedulesShuttle::find($id);
        $shuttleData->delete();
        toast('Your data as been deleted!', 'success');
        return redirect()->route('trip.view');
    }
}
