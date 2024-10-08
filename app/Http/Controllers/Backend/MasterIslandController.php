<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterIsland;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterIslandController extends Controller
{
    // Menampilkan halaman utama dari menu island
    public function index()
    {
        $island = MasterIsland::all();  // Mengambil seluruh data island
        $title = 'Delete Island Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('master.island.index', compact('island'));
    }

    // Menampilkan halaman tambah data
    public function add()
    {
        return view('master.island.add');
    }

    // Menangani proses tambah data
    public function store(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'isd_name' => 'required|max:100',
            'isd_code' => 'required|max:100',
            'isd_slug_en' => 'required',
            'isd_slug_idn' => 'required',
            'isd_image1' => 'required|image',
            'isd_image2' => 'required|image',
            'isd_image3' => 'required|image',
            'isd_image4' => 'image',
            'isd_image5' => 'image',
            'isd_image6' => 'image',
            'isd_map' => 'required',
            'isd_description_en' => 'required',
            'isd_description_idn' => 'required',
            'isd_content_en' => 'required',
            'isd_content_idn' => 'required',
        ]);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            // Menambahkan pesan toast ke dalam session
            toast('Validation failed! Please check your input.', 'error');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Menyimpan inputan ke dalam databas
        $islandData = new MasterIsland();
        $islandData->isd_name = $request->isd_name;
        $islandData->isd_code = $request->isd_code;
        $islandData->isd_slug_en = $request->isd_slug_en;
        $islandData->isd_slug_idn = $request->isd_slug_idn;
        $islandData->isd_keyword = $request->isd_keyword;
        $islandData->isd_map = $request->isd_map;
        $islandData->isd_description_en = $request->isd_description_en;
        $islandData->isd_description_idn = $request->isd_description_idn;
        $islandData->isd_content_en = $request->isd_content_en;
        $islandData->isd_content_idn = $request->isd_content_idn;
        $islandData->isd_updated_by = Auth()->id();

        // Menangani simpan gambar
        if ($request->hasFile('isd_image1')) {
            $islandImage = $request->file('isd_image1')->store('isd_image1');
            $islandData->isd_image1 = $islandImage;
        }
        if ($request->hasFile('isd_image2')) {
            $islandImage = $request->file('isd_image2')->store('isd_image2');
            $islandData->isd_image2 = $islandImage;
        }
        if ($request->hasFile('isd_image3')) {
            $islandImage = $request->file('isd_image3')->store('isd_image3');
            $islandData->isd_image3 = $islandImage;
        }
        if ($request->hasFile('isd_image4')) {
            $islandImage = $request->file('isd_image4')->store('isd_image4');
            $islandData->isd_image4 = $islandImage;
        }
        if ($request->hasFile('isd_image5')) {
            $islandImage = $request->file('isd_image5')->store('isd_image5');
            $islandData->isd_image5 = $islandImage;
        }
        if ($request->hasFile('isd_image6')) {
            $islandImage = $request->file('isd_image6')->store('isd_image6');
            $islandData->isd_image6 = $islandImage;
        }

        // Menangani simpan summernote
        $content_en = $request->isd_content_en;

        $dom = new DOMDocument();
        $dom->loadHTML($content_en, 9);

        $image = $dom->getElementsByTagName('img');

        foreach ($image as $key => $img) {
            $data = base64_decode(explode(',', explode(';', $img->getAttribute('src'))[1])[1]);
            $image_name = "/upload/" . time() . $key . '.png';
            file_put_contents(public_path() . $image_name, $data);

            $img->removeAttribute('src');
            $img->setAttribute('src', $image_name);
        }
        $content_en = $dom->saveHTML();


        $islandData->save();    // Simpan data
        toast('Your data as been submited!', 'success');
        return redirect()->route('island.view');
    }

    // Menampilkan halaman edit data
    public function edit($isd_id)
    {
        $islandEdit = MasterIsland::find($isd_id);  // Menambil id dari data yang dipilih
        return view('master.island.edit', compact('islandEdit'));
    }

    // Menangani proses update data
    public function update(Request $request, $isd_id)
    {
        // Menyimpan inputan ke dalam database
        $islandData = MasterIsland::find($isd_id);  // Mengambil id dari data yang dipilih
        $islandData->isd_name = $request->isd_name;
        $islandData->isd_code = $request->isd_code;
        $islandData->isd_slug_en = $request->isd_slug_en;
        $islandData->isd_slug_idn = $request->isd_slug_idn;
        $islandData->isd_keyword = $request->isd_keyword;
        $islandData->isd_map = $request->isd_map;
        $islandData->isd_description_en = $request->isd_description_en;
        $islandData->isd_description_idn = $request->isd_description_idn;
        $islandData->isd_content_en = $request->isd_content_en;
        $islandData->isd_content_idn = $request->isd_content_idn;
        $islandData->isd_updated_by = Auth()->id();

        // Menangani simpan gambar
        if ($request->hasFile('isd_image1')) {
            $islandImage = $request->file('isd_image1')->store('isd_image1');
            $islandData->isd_image1 = $islandImage;
        }
        if ($request->hasFile('isd_image2')) {
            $islandImage = $request->file('isd_image2')->store('isd_image2');
            $islandData->isd_image2 = $islandImage;
        }
        if ($request->hasFile('isd_image3')) {
            $islandImage = $request->file('isd_image3')->store('isd_image3');
            $islandData->isd_image3 = $islandImage;
        }
        if ($request->hasFile('isd_image4')) {
            $islandImage = $request->file('isd_image4')->store('isd_image4');
            $islandData->isd_image4 = $islandImage;
        }
        if ($request->hasFile('isd_image5')) {
            $islandImage = $request->file('isd_image5')->store('isd_image5');
            $islandData->isd_image5 = $islandImage;
        }
        if ($request->hasFile('isd_image6')) {
            $islandImage = $request->file('isd_image6')->store('isd_image6');
            $islandData->isd_image6 = $islandImage;
        }
        $islandData->update();  // Update data
        toast('Your data as been edited!', 'success');
        return redirect()->route('island.view');
    }

    // Menangani proses hapus data
    public function delete($isd_id)
    {
        $islandDelete = MasterIsland::find($isd_id);    // Menngambil id dari data yang dipilih
        $islandDelete->delete();                    // Menghapus data
        toast('Your data as been deleted!', 'success');
        return redirect()->route('island.view');
    }

    // Menangani menampilkan modal
    public function show($isd_id)
    {
        $islandData = MasterIsland::find($isd_id);  // Mengambil id dari data yang dipilih
        return response()->json($islandData);
    }
}
