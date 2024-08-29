<?php

namespace App\Http\Controllers;

use App\Models\DataCompany;
use App\Models\DataFastboat;
use App\Models\SchedulesSchedule;
use Illuminate\Http\Request;

class DropdownController extends Controller
{
    public function index() {
        $company = DataCompany::get(["cpn_name", "cpn_id"]);
        return view('dropdown', compact('company'));
    }

    public function fetchFastboat(Request $request) {
        $data = DataFastboat::where("fb_company", $request->cpn_id)->get(["fb_name", "fb_id"]);
        return response()->json(['fastboat' => $data]);
    }
    
}
