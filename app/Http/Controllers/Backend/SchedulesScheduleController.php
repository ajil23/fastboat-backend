<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataCompany;
use App\Models\SchedulesSchedule;
use Illuminate\Http\Request;

class SchedulesScheduleController extends Controller
{
    // this function is for view all data from schedule table
    public function index(){
        $scheduleData = SchedulesSchedule::all();
        $title = 'Delete Schedule Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('schedules.schedule.index', compact('scheduleData'));
    }

    // this function is for view form to add schedule data
    public function add(){
        $company = DataCompany::all();
        return view('schedules.schedule.add', compact('company'));
    }

    // this function will request data from input in schedule add form
    public function store(Request $request){
        // Handle the request data validation
        $request->validate([
            'sch_name' => 'required',
            'sch_company' => 'required',
        ]);

        // Handle insert data to database
        $scheduleData = new SchedulesSchedule();
        $scheduleData -> sch_company = $request->sch_company;
        $scheduleData -> sch_name = $request->sch_name;
        $scheduleData -> save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('schedule.view');
    }
    // this function will get the $id of the selected data and then view the schedule edit form
    public function edit($id){
        $scheduleData = SchedulesSchedule::find($id);
        $company = DataCompany::find($id);
        return view('schedules.schedule.edit', compact('schedulData', 'company'));
    }

    //this function will get the $id of the selected data and request data from input in schedule edit from 
    public function update(Request $request, $id){
        // Handle insert data to database
        $scheduleData = SchedulesSchedule::find($id);
        $scheduleData -> sch_company = $request->sch_company;
        $scheduleData -> sch_name = $request->sch_name;
        $scheduleData -> update();
        toast('Your data as been edited!', 'success');
        return redirect()->route('schedule.view');
    }

    // this function will get the $id of selected data and do delete operation
    public function delete($id){
        $scheduleData = SchedulesSchedule::find($id);
        $scheduleData->delete();
        toast('Your data as been deleted!', 'success');
        return redirect()->route('schedule.view');
    }
}
