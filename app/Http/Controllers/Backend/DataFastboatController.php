<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataCompany;
use App\Models\DataFastboat;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class DataFastboatController extends Controller
{
    // Menampilkan halaman utama untuk menu fast-boat
    public function index()
    {
        $fastboat = DataFastboat::all();                // Mengambil seluruh data fast-boat dari database
        $title = 'Delete Fast-boat Data!';              // Title pada modal konfirmasi hapus data
        $text = "Are you sure you want to delete?";     // Teks pada modal konfirmasi hapus data
        confirmDelete($title, $text);                   
        return view('data.fastboat.index', compact('fastboat'));
    }

    // Menampilkan halaman tambah data
    public function add()
    {
        // Mengambil seluruh data company secara ascending berdasarkan nama nya & dengan tipe fast-boat
        $company = DataCompany::orderBy('cpn_name', 'asc')->having('cpn_type', 'fast_boat')->get();
        return view('data.fastboat.add', compact('company'));
    }

    // Menangani proses menyimpan data ke database
    public function store(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'fb_name' => 'required',
            'fb_company' => 'required',
            'fb_image1' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'fb_image2' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'fb_image3' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'fb_keywords' => 'required',
            'fb_slug_en' => 'required',
            'fb_slug_idn' => 'required',
            'fb_description_en' => 'required',
            'fb_description_idn' => 'required',
            'fb_content_en' => 'required',
            'fb_content_idn' => 'required',
        ]);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            // Menambahkan pesan toast ke dalam session
            toast('Validation failed! Please check your input.', 'error');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Menyimpan semua request ke dalam database
        $fastboatData = new DataFastboat();
        $fastboatData->fb_name = $request->fb_name;
        $fastboatData->fb_company = $request->fb_company;
        $fastboatData->fb_keywords = $request->fb_keywords;
        $fastboatData->fb_fasilitas = $request->fb_fasilitas;
        $fastboatData->fb_slug_en = $request->fb_slug_en;
        $fastboatData->fb_slug_idn = $request->fb_slug_idn;
        $fastboatData->fb_description_en = $request->fb_description_en;
        $fastboatData->fb_description_idn = $request->fb_description_idn;
        $fastboatData->fb_content_en = $request->fb_content_en;
        $fastboatData->fb_content_idn = $request->fb_content_idn;
        $fastboatData->fb_term_condition_en = $request->fb_term_condition_en;
        $fastboatData->fb_term_condition_idn = $request->fb_term_condition_idn;
        $fastboatData->fb_updated_by = Auth()->id();

        // Penanganan simpan gambar
        if ($request->hasFile('fb_image1')) {
            $fbimg1 = $request->file('fb_image1')->store('fb_image1');
            $fastboatData->fb_image1 = $fbimg1;
        }
        if ($request->hasFile('fb_image2')) {
            $fbimg2 = $request->file('fb_image2')->store('fb_image2');
            $fastboatData->fb_image2 = $fbimg2;
        }
        if ($request->hasFile('fb_image3')) {
            $fbimg3 = $request->file('fb_image3')->store('fb_image3');
            $fastboatData->fb_image3 = $fbimg3;
        }
        if ($request->hasFile('fb_image4')) {
            $fbimg4 = $request->file('fb_image4')->store('fb_image4');
            $fastboatData->fb_image4 = $fbimg4;
        }
        if ($request->hasFile('fb_image5')) {
            $fbimg5 = $request->file('fb_image5')->store('fb_image5');
            $fastboatData->fb_image5 = $fbimg5;
        }
        if ($request->hasFile('fb_image6')) {
            $fbimg6 = $request->file('fb_image6')->store('fb_image6');
            $fastboatData->fb_image6 = $fbimg6;
        }

        // Penanganan menyimpan inputan summernote
        $content = $request->fb_content_en;

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


        $fastboatData->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('fast-boat.view');
    }

    // Menampilkan halaman edit data fast-boat
    public function edit($fb_id)
    {
        $company = DataCompany::all();              // Menampilkan seluruh data company
        $fastboatEdit = DataFastboat::find($fb_id);   // Mengambil id dari data yang di pilih

        // Menggunakan compact untuk mengirimkan variabel ke halaman edit data
        return view('data.fastboat.edit', compact('fastboatEdit', 'company'));
    }

    // this function will get the $id of the selected data and request data from input in fast boat edit from
    public function update(Request $request, $fb_id)
    {
        // Handle insert data to database
        $fastboatData = DataFastboat::find($fb_id);
        $fastboatData->fb_name = $request->fb_name;
        $fastboatData->fb_company = $request->fb_company;
        $fastboatData->fb_keywords = $request->fb_keywords;
        $fastboatData->fb_fasilitas = $request->fb_fasilitas;
        $fastboatData->fb_slug_en = $request->fb_slug_en;
        $fastboatData->fb_slug_idn = $request->fb_slug_idn;
        $fastboatData->fb_description_en = $request->fb_description_en;
        $fastboatData->fb_description_idn = $request->fb_description_idn;
        $fastboatData->fb_content_en = $request->fb_content_en;
        $fastboatData->fb_content_idn = $request->fb_content_idn;
        $fastboatData->fb_term_condition_en = $request->fb_term_condition_en;
        $fastboatData->fb_term_condition_idn = $request->fb_term_condition_idn;
        $fastboatData->fb_updated_by = Auth()->id();

        // handle image store
        if ($request->hasFile('fb_image1')) {
            $fbimg1 = $request->file('fb_image1')->store('fb_image1');
            $fastboatData->fb_image1 = $fbimg1;
        }
        if ($request->hasFile('fb_image2')) {
            $fbimg2 = $request->file('fb_image2')->store('fb_image2');
            $fastboatData->fb_image2 = $fbimg2;
        }
        if ($request->hasFile('fb_image3')) {
            $fbimg3 = $request->file('fb_image3')->store('fb_image3');
            $fastboatData->fb_image3 = $fbimg3;
        }
        if ($request->hasFile('fb_image4')) {
            $fbimg4 = $request->file('fb_image4')->store('fb_image4');
            $fastboatData->fb_image4 = $fbimg4;
        }
        if ($request->hasFile('fb_image5')) {
            $fbimg5 = $request->file('fb_image5')->store('fb_image5');
            $fastboatData->fb_image5 = $fbimg5;
        }
        if ($request->hasFile('fb_image6')) {
            $fbimg6 = $request->file('fb_image6')->store('fb_image6');
            $fastboatData->fb_image6 = $fbimg6;
        }
        $fastboatData->update();
        toast('Your data as been edited!', 'success');
        return redirect()->route('fast-boat.view');
    }

    // Menjalankan operasi hapus data
    public function delete($fb_id)
    {
        $fastboatData = DataFastboat::find($fb_id);        // Mengambil id dari data yang di pilih
        $fastboatData->delete();                       // Perintah untuk menghapus data
        toast('Your data as been deleted!', 'success');
        return redirect()->route('fast-boat.view');
    }

    // Menampilkan modal detail data fast-boat
    public function show($fb_id)
    {
        $fastboatData = DataFastboat::with(['company'])->findOrFail($fb_id); // Mengambil seluruh data fast-boat beserta company nya
        return response()->json($fastboatData);
    }

    // Menangani perubahan status 
    public function status($fb_id)
    {
        $fastboatData = DataFastboat::find($fb_id);    // Mengambil id dari data yang di pilih

        // Proses pengubahan status
        if ($fastboatData) {
            if ($fastboatData->fb_status) {
                $fastboatData->fb_status = 0;
            } else {
                $fastboatData->fb_status = 1;
            }
            $fastboatData->save();
        }
        return back();
    }

}
