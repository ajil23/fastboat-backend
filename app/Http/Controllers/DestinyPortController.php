<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DestinyPortController extends Controller
{
    public function index () {
        return view('destiny.port.index');
    }
}
