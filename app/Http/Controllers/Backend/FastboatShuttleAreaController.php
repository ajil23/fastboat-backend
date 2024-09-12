<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FastboatShuttleArea;
use App\Models\MasterIsland;
use Illuminate\Http\Request;

class FastboatShuttleAreaController extends Controller
{
    // this function is for view all data from shuttlearea table
    public function index()
    {
        $shuttlearea = FastboatShuttleArea::paginate(10);
        $island = MasterIsland::all();
        $title = 'Delete Shuttle Area Data Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('fast-boat.shuttlearea.index', compact('shuttlearea', 'island'));
    }

    // this function will request data from input in shuttlearea add form
    public function store(Request $request)
    {
        // Handle the request data validation
        $request->validate([
            'sa_name' => 'required',
            'sa_island' => 'required',
        ]);

        // Handle insert data to database
        $shuttleareaData = new FastboatShuttleArea();
        $shuttleareaData->sa_island = $request->sa_island;
        $shuttleareaData->sa_name = $request->sa_name;
        $shuttleareaData->sa_updated_by = Auth()->id();
        $shuttleareaData->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('shuttlearea.view');
    }

    // this function will get the $id of the selected data and then view the shuttlearea edit form
    public function edit($id)
    {
        $shuttleareaData = FastboatShuttleArea::find($id);
        $island = MasterIsland::all();
        return view('fast-boat.shuttlearea.edit', compact('shuttleareaData', 'island'));
    }

    //this function will get the $id of the selected data and request data from input in shuttlearea edit from 
    public function update(Request $request, $id)
    {
        // Handle insert data to database
        $shuttleareaData = FastboatShuttleArea::find($id);
        $shuttleareaData->sa_island = $request->sa_island;
        $shuttleareaData->sa_name = $request->sa_name;
        $shuttleareaData->sa_updated_by = Auth()->id();
        $shuttleareaData->update();
        toast('Your data as been edited!', 'success');
        return redirect()->route('shuttlearea.view');
    }

    // this function will get the $id of selected data and do delete operation
    public function delete($id)
    {
        $scheduleData = FastboatShuttleArea::find($id);
        $scheduleData->delete();
        toast('Your data as been deleted!', 'success');
        return redirect()->route('schedule.view');
    }
}
