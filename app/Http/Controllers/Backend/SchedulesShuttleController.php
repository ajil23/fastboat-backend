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
        $shuttleData = SchedulesShuttle::with(['trip.schedule.company'])->get();
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
        // Looping melalui array trip
        foreach ($request->s_trip as $trip) {
            // Looping melalui array shuttle info
            for ($i = 0; $i < count($request->s_area); $i++) {
                // Cek apakah data sudah ada di database
                $existingData = SchedulesShuttle::where([
                    ['s_trip', $trip],
                    ['s_area', $request->s_area[$i]],
                    ['s_start', $request->s_start[$i]],
                    ['s_end', $request->s_end[$i]],
                ])->first();

                if ($existingData) {
                    // Update data yang sudah ada
                    $existingData->update([
                        's_trip' => $trip,
                        's_area' => $request->s_area[$i],
                        's_start' => $request->s_start[$i],
                        's_end' => $request->s_end[$i],
                        's_meeting_point' => $request->s_meeting_point[$i],
                        's_updated_by' => auth()->id(),
                    ]);
                } else {
                    // Buat data baru
                    $shuttleData = new SchedulesShuttle();
                    $shuttleData->s_trip = $trip;
                    $shuttleData->s_area = $request->s_area[$i];
                    $shuttleData->s_start = $request->s_start[$i];
                    $shuttleData->s_end = $request->s_end[$i];
                    $shuttleData->s_meeting_point = $request->s_meeting_point[$i];
                    $shuttleData->s_updated_by = auth()->id();
                    $shuttleData->save();
                }
            }
        }

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

    public function multipleAdd(Request $request)
{
    $selectedIds = $request->input('selected_ids', []);
    
    foreach ($selectedIds as $id) {
        $shuttleData = SchedulesShuttle::find($id);
        if ($shuttleData) {
            $shuttleData->update([
                's_start' => $request->s_start[$id] ?? $shuttleData->s_start,
                's_end' => $request->s_end[$id] ?? $shuttleData->s_end,
                's_meeting_point' => $request->s_meeting_point[$id] ?? $shuttleData->s_meeting_point,
                's_updated_by' => auth()->id(),
            ]);
        }
    }

    return redirect()->back()->with('success', 'Selected items updated successfully.');
}
}
