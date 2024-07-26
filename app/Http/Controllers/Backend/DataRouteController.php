<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataRoute;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataRouteController extends Controller
{
    // this function is for view all data from route table
    public function index()
    {
        $route = DataRoute::all();
        $title = 'Delete Route Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('data.route.index', compact('route'));
    }

    // this function is for view form to add route data
    public function add()
    {
        return view('data.route.add');
    }

    // this function will request data from input in route add form
    public function store(Request $request)
    {
        // Handle the request data validation
        $request->validate([
            'rt_dept_island' => 'required',
            'rt_arrival_island' => 'required'
        ]);

        // Handle insert data to database
        $routeData = new DataRoute();
        $routeData->rt_dept_island = $request->rt_dept_island;
        $routeData->rt_arrival_island = $request->rt_arrival_island;
        $routeData->rt_updated_by = Auth()->id();
        $routeData->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('route.view');
    }

    // this function will get the $id of the selected data and then view the route edit form
    public function edit($id)
    {
        $routeEdit = DataRoute::find($id);
        return view('data.route.edit', compact('routeEdit'));
    }

    // this function will get the $id of the selected data and request data from input in route edit from
    public function update(Request $request, $id)
    {

        // Handle update data to database
        $routeData = DataRoute::find($id);
        $routeData->rt_dept_island = $request->rt_dept_island;
        $routeData->rt_arrival_island = $request->rt_arrival_island;
        $routeData->rt_updated_by = Auth()->id();
        $routeData->save();
        toast('Your data as been edited!', 'success');
        return redirect()->route('route.view');
    }

    // this function will get the $id of selected data and do delete operation
    public function delete($id)
    {
        $routeDelete = DataRoute::find($id);
        $routeDelete->delete();
        toast('Your data as been deleted!', 'success');
        return redirect()->route('route.view');
    }
}
