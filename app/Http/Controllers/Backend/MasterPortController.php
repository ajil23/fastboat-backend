<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterIsland;
use App\Models\MasterPort;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterPortController extends Controller
{
    // Menampilkan halaman utama dari menu port
    public function index()
    {
        // Mengambil seluruh data port dengan urutan ascending berdasarkan nama & paginasi data sebanyak 15
        $port = MasterPort::orderBy('prt_name_en', 'asc')->paginate(15);    
        $island = MasterIsland::all();  // Mengambil seluruh data island
        $title = 'Delete Port Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('master.port.index', compact('port', 'island'));
    }

    //Menampilkan halaman tambah data port
    public function add()
    {
        $island = MasterIsland::all();  // Mengambil seluruh data island
        return view('master.port.add', compact('island'));
    }

    // Menangani proses tambah data
    public function store(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
           'prt_name_en' => 'required|max:100',
            'prt_name_idn' => 'required|max:100',
            'prt_code' => 'required|max:100',
            'prt_slug_en' => 'required',
            'prt_slug_idn' => 'required',
            'prt_image1' => 'required|image',
            'prt_image2' => 'required|image',
            'prt_image3' => 'required|image',
            'prt_image4' => 'image',
            'prt_image5' => 'image',
            'prt_image6' => 'image',
            'prt_map' => 'required',
            'prt_description_en' => 'required',
            'prt_description_idn' => 'required',
            'prt_content_en' => 'required',
            'prt_content_idn' => 'required',
        ]);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            // Menambahkan pesan toast ke dalam session
            toast('Validation failed! Please check your input.', 'error');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Proses menambahkan data ke dalam database
        $portData = new MasterPort();
        $portData->prt_name_en = $request->prt_name_en;
        $portData->prt_name_idn = $request->prt_name_idn;
        $portData->prt_island = $request->prt_island;
        $portData->prt_address = $request->prt_address;
        $portData->prt_code = $request->prt_code;
        $portData->prt_slug_en = $request->prt_slug_en;
        $portData->prt_slug_idn = $request->prt_slug_idn;
        $portData->prt_keyword = $request->prt_keyword;
        $portData->prt_map = $request->prt_map;
        $portData->prt_description_en = $request->prt_description_en;
        $portData->prt_description_idn = $request->prt_description_idn;
        $portData->prt_content_en = $request->prt_content_en;
        $portData->prt_content_idn = $request->prt_content_idn;
        $portData->prt_updated_by = Auth()->id();

        // Penanganan simpan gambar
        if ($request->hasFile('prt_image1')) {
            $portImage = $request->file('prt_image1')->store('prt_image1');
            $portData->prt_image1 = $portImage;
        }
        if ($request->hasFile('prt_image2')) {
            $portImage = $request->file('prt_image2')->store('prt_image2');
            $portData->prt_image2 = $portImage;
        }
        if ($request->hasFile('prt_image3')) {
            $portImage = $request->file('prt_image3')->store('prt_image3');
            $portData->prt_image3 = $portImage;
        }
        if ($request->hasFile('prt_image4')) {
            $portImage = $request->file('prt_image4')->store('prt_image4');
            $portData->prt_image4 = $portImage;
        }
        if ($request->hasFile('prt_image5')) {
            $portImage = $request->file('prt_image5')->store('prt_image5');
            $portData->prt_image5 = $portImage;
        }
        if ($request->hasFile('prt_image6')) {
            $portImage = $request->file('prt_image6')->store('prt_image6');
            $portData->prt_image6 = $portImage;
        }

        // Penanganan simpan summernote
        $content = $request->prt_content_en;

        $dom = new DOMDocument();
        $dom->loadHTML($content, 9);

        $image = $dom->getElementsByTagName('img');

        foreach ($image as $key => $img) {
            $data = base64_decode(explode(',', explode(';', $img->getAttribute('src'))[1])[1]);
            $image_name = "/upload/" . time() . $key . '.png';
            file_put_contents(public_path() . $image_name, $data);

            $img->removeAttribute('src');
            $img->setAttribute('src', $image_name);
        }
        $content = $dom->saveHTML();

        $portData->save();  // Simpan data
        toast('Your data as been submited!', 'success');
        return redirect()->route('port.view');
    }

    // Menampilkan halaman edit data
    public function edit($prt_id)
    {
        $portEdit = MasterPort::find($prt_id);  // Mengambil id dari data yang dipilih
        $island = MasterIsland::all();          // Mengambil seluruh data island
        return view('master.port.edit', compact('portEdit', 'island'));
    }

    // Menangani proses update data
    public function update(Request $request, $id)
    {
        // Proses merubah data dari database
        $portData = MasterPort::find($id);  //Mengambil id dari data yang dipilih
        $portData->prt_name_en = $request->prt_name_en;
        $portData->prt_name_idn = $request->prt_name_idn;
        $portData->prt_island = $request->prt_island;
        $portData->prt_address = $request->prt_address;
        $portData->prt_code = $request->prt_code;
        $portData->prt_slug_en = $request->prt_slug_en;
        $portData->prt_slug_idn = $request->prt_slug_idn;
        $portData->prt_keyword = $request->prt_keyword;
        $portData->prt_map = $request->prt_map;
        $portData->prt_description_en = $request->prt_description_en;
        $portData->prt_description_idn = $request->prt_description_idn;
        $portData->prt_content_en = $request->prt_content_en;
        $portData->prt_content_idn = $request->prt_content_idn;
        $portData->prt_updated_by = Auth()->id();

        // Penanganan simpan gambar
        if ($request->hasFile('prt_image1')) {
            $portImage = $request->file('prt_image1')->store('prt_image1');
            $portData->prt_image1 = $portImage;
        }
        if ($request->hasFile('prt_image2')) {
            $portImage = $request->file('prt_image2')->store('prt_image2');
            $portData->prt_image2 = $portImage;
        }
        if ($request->hasFile('prt_image3')) {
            $portImage = $request->file('prt_image3')->store('prt_image3');
            $portData->prt_image3 = $portImage;
        }
        if ($request->hasFile('prt_image4')) {
            $portImage = $request->file('prt_image4')->store('prt_image4');
            $portData->prt_image4 = $portImage;
        }
        if ($request->hasFile('prt_image5')) {
            $portImage = $request->file('prt_image5')->store('prt_image5');
            $portData->prt_image5 = $portImage;
        }
        if ($request->hasFile('prt_image6')) {
            $portImage = $request->file('prt_image6')->store('prt_image6');
            $portData->prt_image6 = $portImage;
        }
        $portData->update();    // Simpan data yang telah di update
        toast('Your data as been edited!', 'success');
        return redirect()->route('port.view');
    }

    // Menangani proses hapus data
    public function delete($prt_id)
    {
        $portDelete = MasterPort::find($prt_id);    // Mengambil id dari data yang dipilih
        $portDelete->delete();                      // Menjalankan proses hapus data
        toast('Your data as been deleted!', 'success');
        return redirect()->route('port.view');
    }

    // Menampilkan modal
    public function show($id)
    {
        // Mengambil data sesuai dengan yang dipilih serta mengambil data island nya
        $portData = MasterPort::with(['island'])->find($id);   
        return response()->json($portData);
    }
}
