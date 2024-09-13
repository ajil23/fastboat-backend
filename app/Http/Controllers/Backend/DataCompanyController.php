<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DataCompanyController extends Controller
{
    // Menampilkan halaman utama menu company
    public function index()
    {
        // Mengambil seluruh data company di urutkan secara ascending berdasarkan nama & melakukan paginasi 15 data
        $company = DataCompany::orderBy('cpn_name', 'asc')->paginate(15);
        $title = 'Delete Company Data!';                // Title pada modal konfirmasi hapus data
        $text = "Are you sure you want to delete?";     // Teks pada modal konfirmasi hapus data
        confirmDelete($title, $text);
        return view('data.company.index', compact('company'));
    }

    // Menampilkan halaman tambah data company
    public function add()
    {
        return view('data.company.add');
    }

    // Menangani proses tambah data
    public function store(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'cpn_name' => 'required',
            'cpn_email' => 'required',
            'cpn_phone' => 'required|numeric',
            'cpn_whatsapp' => 'required|numeric',
            'cpn_logo' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'cpn_address' => 'required',
            'cpn_type' => 'required',
        ]);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            // Menambahkan pesan toast ke dalam session
            toast('Validation failed! Please check your input.', 'error');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        // Menyimpan ke dalam database
        $companyData = new DataCompany();
        $companyData->cpn_name = $request->cpn_name;
        $companyData->cpn_email = $request->cpn_email;
        $companyData->cpn_phone = $request->cpn_phone;
        $companyData->cpn_whatsapp = $request->cpn_whatsapp;
        $companyData->cpn_address = $request->cpn_address;
        $companyData->cpn_website = $request->cpn_website;
        $companyData->cpn_type = $request->cpn_type;
        $companyData->cpn_updated_by = Auth()->id();

        // Penanganan inputan gambar
        if ($request->hasFile('cpn_logo')) {
            $companyLogo = $request->file('cpn_logo')->store('cpn_logo');
            $companyData->cpn_logo = $companyLogo;
        }

        // Simpan data
        $companyData->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('company.view');
    }

    // Menampilkan halaman edit data
    public function edit($cpn_id)
    {
        // Mengambil id data yang di edit
        $companyEdit = DataCompany::find($cpn_id);

        // Mengambil info logo dari data yang di pilih
        $logoInfo = $companyEdit->cpn_logo;
        return view('data.company.edit', compact('companyEdit', 'logoInfo'));
    }

    // Menangani proses edit data
    public function update(Request $request, $cpn_id)
    {
        // Menyimpan data inputan
        $companyData = DataCompany::find($cpn_id);      // Mengambil id dari data yang di pilih
        $companyData->cpn_name = $request->cpn_name;
        $companyData->cpn_email = $request->cpn_email;
        $companyData->cpn_phone = $request->cpn_phone;
        $companyData->cpn_whatsapp = $request->cpn_whatsapp;
        $companyData->cpn_address = $request->cpn_address;
        $companyData->cpn_website = $request->cpn_website;
        $companyData->cpn_type = $request->cpn_type;
        $companyData->cpn_updated_by = Auth()->id();

        // Penanganan edit gambar
        if ($request->hasFile('cpn_logo')) {
            Storage::delete($companyData->cpn_logo);
            $companyLogo = $request->file('cpn_logo')->store('cpn_logo');
            $companyData->cpn_logo = $companyLogo;
        }

        $companyData->update();     // Menyimpan hasil update
        toast('Your data as been edited!', 'success');
        return redirect()->route('company.view');
    }

    // Menangani hapus data
    public function delete($cpn_id)
    {
        // Mengambil id dari data yang di pilih
        $companyDelete = DataCompany::find($cpn_id);

        // Melakukan penghapusan data
        $companyDelete->delete();
        toast('Your data as been deleted!', 'success');
        return redirect()->route('company.view');
    }

    // Menampilkan detail data
    public function show($cpn_id)
    {
        // Mencari id dari data yang di pilih
        $companyData = DataCompany::find($cpn_id);

        // Mengembalikan value dari data yang di pilih dalam bentuk json
        return response()->json($companyData);
    }

    // Menangani perubahan email status
    public function emailStatus($cpn_id)
    {
        $companyData = DataCompany::find($cpn_id);

        if ($companyData) {
            if ($companyData->cpn_email_status) {
                $companyData->cpn_email_status = 0;
            } else {
                $companyData->cpn_email_status = 1;
            }
            $companyData->save();
        }
        return back();
    }

    // Menangani perubahan company status
    public function companyStatus($cpn_id)
    {
        $companyData = DataCompany::find($cpn_id);

        if ($companyData) {
            if ($companyData->cpn_status) {
                $companyData->cpn_status = 0;
            } else {
                $companyData->cpn_status = 1;
            }
            $companyData->save();
        }
        return back();
    }
}
