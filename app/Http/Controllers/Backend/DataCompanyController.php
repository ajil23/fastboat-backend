<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataCompanyController extends Controller
{
    // Menampilkan data company serta title dan text pada modal konfirmasi hapus data
    public function index()
    {
        $company = DataCompany::orderBy('cpn_name', 'asc')->paginate(15);
        $title = 'Delete Company Data!';
        $text = "Are you sure you want to delete?";
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
        // Validasi data inputan
        $request->validate([
            'cpn_name' => 'required',
            'cpn_email' => 'required',
            'cpn_phone' => 'required|numeric',
            'cpn_whatsapp' => 'required|numeric',
            'cpn_logo' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'cpn_address' => 'required',
            'cpn_type' => 'required',
        ]);

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
    public function edit($id)
    {
        // Mengambil id data yang di edit
        $companyEdit = DataCompany::find($id);

        // Mengambil info logo dari data yang di pilih
        $logoInfo = $companyEdit->cpn_logo;
        return view('data.company.edit', compact('companyEdit', 'logoInfo'));
    }

    // Menangani proses edit data
    public function update(Request $request, $id)
    {
        // Mengedit data
        $companyData = DataCompany::find($id);
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

        // Menyimpan hasil update
        $companyData->update();
        toast('Your data as been edited!', 'success');
        return redirect()->route('company.view');
    }

    // Menangani hapus data
    public function delete($id)
    {
        // Mengambil id dari data yang di pilih
        $companyDelete = DataCompany::find($id);

        // Melakukan penghapusan data
        $companyDelete->delete();
        toast('Your data as been deleted!', 'success');
        return redirect()->route('company.view');
    }

    // Menampilkan detail data
    public function show($id)
    {
        // Mencari id dari data yang di pilih
        $companyData = DataCompany::find($id);

        // Mengembalikan value dari data yang di pilih dalam bentuk json
        return response()->json($companyData);
    }

    // Menangani perubahan email status
    public function emailStatus($id)
    {
        $companyData = DataCompany::find($id);

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
    public function companyStatus($id)
    {
        $companyData = DataCompany::find($id);

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
