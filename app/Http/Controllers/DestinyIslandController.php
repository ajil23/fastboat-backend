<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DestinyIslandController extends Controller
{
    public function index () {
        return view('destiny.island.index');
    }
}
