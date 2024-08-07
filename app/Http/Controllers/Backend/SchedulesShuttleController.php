<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataCompany;
use App\Models\SchedulesShuttle;
use App\Models\SchedulesShuttleArea;
use App\Models\SchedulesTrip;
use Illuminate\Http\Request;

class SchedulesShuttleController extends Controller
{
    // this function is for view all data from shuttlearea table
    public function index(){
        $shuttleData = SchedulesShuttle::with(['trip.schedule.company'])->orderBy('s_trip', 'asc')->orderBy('s_start', 'asc')->get();
        $trip = SchedulesTrip::with(['departure.arrival']);
        $area = SchedulesShuttleArea::all();
        $company = DataCompany::all();
        $title = 'Delete Shuttle Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('schedules.shuttle.index', compact('shuttleData', 'trip', 'area', 'company'));
    }

    public function add()
    {
        $trip = SchedulesTrip::all();
        $area = SchedulesShuttleArea::all();
        $company = DataCompany::orderBy('cpn_name', 'asc')->get();
        return view('schedules.shuttle.add', compact('trip', 'area', 'company'));
    }

    public function store(Request $request)
    {
        // Validasi request
        // $request->validate([
        //     's_trip' => 'required|exists:schedules_trips,id',
        //     's_area' => 'required|exists:schedules_shuttle_areas,id',
        //     's_start' => 'required|date',
        //     's_end' => 'required|date',
        //     's_meeting_point' => 'required|string|max:255',
        // ]);

        // Pastikan input bukan array
        $s_trip = is_array($request->s_trip) ? implode(',', $request->s_trip) : $request->s_trip;
        $s_area = is_array($request->s_area) ? implode(',', $request->s_area) : $request->s_area;
        $s_start = is_array($request->s_start) ? implode(',', $request->s_start) : $request->s_start;
        $s_end = is_array($request->s_end) ? implode(',', $request->s_end) : $request->s_end;
        $s_meeting_point = is_array($request->s_meeting_point) ? implode(',', $request->s_meeting_point) : $request->s_meeting_point;

        // Handle insert data to database
        $shuttleData = new SchedulesShuttle();
        $shuttleData->s_trip = $s_trip;
        $shuttleData->s_area = $s_area;
        $shuttleData->s_start = $s_start;
        $shuttleData->s_end = $s_end;
        $shuttleData->s_meeting_point = $s_meeting_point;
        $shuttleData->s_updated_by = auth()->id(); // using auth() helper
        $shuttleData->save();

        toast('Your data has been submitted!', 'success');
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

    public function multiple(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);
        $s_start = $request->input('s_start', null);
        $s_end = $request->input('s_end', null);
        $s_meeting_point = $request->input('s_meeting_point', null);
    
        foreach ($selectedIds as $id) {
            $shuttleData = SchedulesShuttle::find($id);
            if ($shuttleData) {
                if (!is_null($s_start)) {
                    $shuttleData->s_start = $s_start;
                }
                if (!is_null($s_end)) {
                    $shuttleData->s_end = $s_end;
                }
                if (!is_null($s_meeting_point)) {
                    $shuttleData->s_meeting_point = $s_meeting_point;
                }
                $shuttleData->save();
            }
        }
    
        return redirect()->back()->with('success', 'Selected items updated successfully.');
    }
    
    
    

    // this function will get the $id of selected data and do delete operation
    public function delete($id)
    {
        $shuttleData = SchedulesShuttle::find($id);
        $shuttleData->delete();
        toast('Your data as been deleted!', 'success');
        return redirect()->route('shuttle.view');
    }

    public function deleteMultiple(Request $request)
    {
        $selectedIds = explode(',', $request->input('selected_ids', ''));
        SchedulesShuttle::whereIn('s_id', $selectedIds)->delete();
        toast('Your data as been deleted!', 'success');
        return redirect()->route('shuttle.view');
    }
}
