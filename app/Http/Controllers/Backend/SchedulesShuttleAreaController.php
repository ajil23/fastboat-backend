<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterIsland;
use App\Models\SchedulesShuttleArea;
use App\Models\SchedulesSchedule;
use Illuminate\Http\Request;

class SchedulesShuttleAreaController extends Controller
{
    // this function is for view all data from shuttlearea table
    public function index(){
        $shuttlearea = SchedulesShuttleArea::paginate(10);
        $island = MasterIsland::all();
        $title = 'Delete Shuttle Area Data Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('schedules.shuttlearea.index', compact('shuttlearea', 'island'));
    }

    // this function will request data from input in shuttlearea add form
    public function store(Request $request){
        // Handle the request data validation
        $request->validate([
            'sa_name' => 'required',
            'sa_island' => 'required',
        ]);

        // Handle insert data to database
        $shuttleareaData = new SchedulesShuttleArea();
        $shuttleareaData -> sa_island = $request->sa_island;
        $shuttleareaData -> sa_name = $request->sa_name;
        $shuttleareaData -> sa_updated_by = Auth()->id();
        $shuttleareaData -> save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('shuttlearea.view');
    }

    // this function will get the $id of the selected data and then view the shuttlearea edit form
    public function edit($id){
        $shuttleareaData = SchedulesShuttleArea::find($id);
        $island = MasterIsland::all();
        return view('schedules.shuttlearea.edit', compact('shuttleareaData', 'island'));
    }

    //this function will get the $id of the selected data and request data from input in shuttlearea edit from 
    public function update(Request $request, $id){
        // Handle insert data to database
        $shuttleareaData = SchedulesShuttleArea::find($id);
        $shuttleareaData -> sa_island = $request->sa_island;
        $shuttleareaData -> sa_name = $request->sa_name;
        $shuttleareaData -> sa_updated_by = Auth()->id();
        $shuttleareaData -> update();
        toast('Your data as been edited!', 'success');
        return redirect()->route('shuttlearea.view');
    }

    // this function will get the $id of selected data and do delete operation
    public function delete($id){
        $scheduleData = SchedulesShuttleArea::find($id);
        $scheduleData->delete();
        toast('Your data as been deleted!', 'success');
        return redirect()->route('schedule.view');
    }
}
