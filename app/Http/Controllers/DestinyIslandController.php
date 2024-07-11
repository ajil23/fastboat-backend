<?php

namespace App\Http\Controllers;

use App\Models\DestinyIsland;
use Illuminate\Http\Request;

class DestinyIslandController extends Controller
{
    // this function is for view all data from island table
    public function index () {
        $dataisland = DestinyIsland::all();
        return view('destiny.island.index', compact('dataisland'));
    }

    // this function is for view form to add island data
    public function add () {
        $dataisland = DestinyIsland::all();
        return view('destiny.island.add', compact('dataisland'));
    }

    // this function is for 
    public function store (Request $request) {
        return redirect()->route('destiny.island.index')->with('message', 'Data berhasil ditambahkan!');
    }

    public function edit ($id) {
        $editisland = DestinyIsland::find($id);
        return view ('destiny.island.edit', compact('editesland'));
    }

    public function update ($id) {
        return redirect()->route('destiny.island.index')->with('message', 'Data Island berhasil diubah!');
    }
    
    public function delete($id){
        $dataguru = DestinyIsland::find($id);
        $dataguru->delete();
        return redirect()->route('destiny.island.index')->with('message', 'Data Island berhasil dihapus!');
    }
}
