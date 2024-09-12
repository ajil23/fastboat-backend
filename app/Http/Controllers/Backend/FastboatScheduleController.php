<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataCompany;
use App\Models\FastboatSchedule;
use Illuminate\Http\Request;

class FastboatScheduleController extends Controller
{
        // this function is for view all data from schedule table
        public function index()
        {
            $scheduleData = FastboatSchedule::all();
            $company = DataCompany::all();
            $title = 'Delete Schedule Data!';
            $text = "Are you sure you want to delete?";
            confirmDelete($title, $text);
            return view('fast-boat.schedule.index', compact('scheduleData', 'company'));
        }
    
        // this function will request data from input in schedule add form
        public function store(Request $request)
        {
            // Handle the request data validation
            $request->validate([
                'sch_name' => 'required',
                'sch_company' => 'required',
            ]);
    
            // Handle insert data to database
            $scheduleData = new FastboatSchedule();
            $scheduleData->sch_company = $request->sch_company;
            $scheduleData->sch_name = $request->sch_name;
            $scheduleData->sch_updated_by = Auth()->id();
            $scheduleData->save();
            toast('Your data as been submited!', 'success');
            return redirect()->route('schedule.view');
        }
    
        // this function will get the $id of the selected data and then view the schedule edit form
        public function edit($id)
        {
            $scheduleData = FastboatSchedule::find($id);
            $company = DataCompany::all();
            return response()->json($scheduleData, $company);
        }
    
        //this function will get the $id of the selected data and request data from input in schedule edit from 
        public function update(Request $request, $id)
        {
            $schedule = FastboatSchedule::findOrFail($id);
            $schedule->sch_company = $request->sch_company;
            $schedule->sch_name = $request->sch_name;
            $schedule->sch_updated_by = Auth()->id();
            $schedule->update();
            toast('Your data as been edited!', 'success');
            return redirect()->route('schedule.view');
        }
    
    
        // this function will get the $id of selected data and do delete operation
        public function delete($id)
        {
            $scheduleData = FastboatSchedule::find($id);
            $scheduleData->delete();
            toast('Your data as been deleted!', 'success');
            return redirect()->route('schedule.view');
        }
}
