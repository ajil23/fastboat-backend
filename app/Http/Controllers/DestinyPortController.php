<?php

namespace App\Http\Controllers;

use App\Models\DestinyPort;
use Illuminate\Http\Request;

class DestinyPortController extends Controller
{
    public function index () {
        $dataport = DestinyPort::all();
        return view('destiny.port.index');
    }

    // this function is for view form to add port data
    public function add () {
        $dataport = DestinyPort::all();
        return view('destiny.port.add', compact('dataport'));
    }
}
