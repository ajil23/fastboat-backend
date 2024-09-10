<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterCurrencyController extends Controller
{
    public function index()
    {
        $currency = MasterCurrency::all();
        $title = 'Delete Currency Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('master.currency.index', compact('currency'));
    }

    public function store(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'cy_name' => 'required|string|max:255',
            'cy_code' => 'required|string|max:3',
        ]);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            // Menambahkan pesan toast ke dalam session
            toast('Validation failed! Please check your input.', 'error');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Menangani tambah data ke database
        $currencyData = new MasterCurrency();
        $currencyData->cy_name = $request->cy_name;
        $currencyData->cy_code = $request->cy_code;
        $currencyData->cy_updated_by = Auth()->user()->name;
        $currencyData->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('currency.view');
    }

    public function editBulk(Request $request)
    {
        // Ambil semua ID currency yang dikirim
        $currencyIds = $request->input('currency_ids');

        // Ambil semua data currency berdasarkan ID
        $currencies = MasterCurrency::whereIn('cy_id', $currencyIds)->get();

        // Arahkan ke halaman edit dengan data currency yang dipilih
        return view('master.currency.edit', compact('currencies'));
    }

    public function updateBulk(Request $request)
    {
        $currencyData = $request->except('_token');

        foreach ($currencyData['cy_name'] as $cy_id => $cy_name) {
            MasterCurrency::where('cy_id', $cy_id)->update([
                'cy_rate' => str_replace(',', '.', str_replace('.', '', $currencyData['cy_rate'][$cy_id])),
                'cy_updated_by' => auth()->user()->name, // Atau logic untuk mengambil user saat ini
            ]);
        }

        toast('Your data as been submited!', 'success');
        return redirect()->route('currency.view');
    }


    // Menangani update data
    public function update(Request $request, $cy_id)
    {
        $validator = Validator::make($request->all(), [
            'cy_name' => 'string|max:255',
            'cy_code' => 'string|max:3',
            'cy_rate' => 'numeric|between:0,999999.99999999',
        ]);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            // Menambahkan pesan toast ke dalam session
            toast('Validation failed! Please check your input.', 'error');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $currencyUpdate = MasterCurrency::find($cy_id);
        $currencyUpdate->cy_name = $request->cy_name;
        $currencyUpdate->cy_code = $request->cy_code;
        $currencyUpdate->cy_rate = $request->cy_rate;
        $currencyUpdate->cy_updated_by = Auth()->user()->name;
        $currencyUpdate->update();
        toast('Your data as been submited!', 'success');
        return redirect()->route('currency.view');
    }

    // Menangani hapus data
    public function delete($cy_id)
    {
        // Mendapatkan id dari data yang di pilih
        $currencyDelete = MasterCurrency::find($cy_id);

        // Melakukan proses hapus data
        $currencyDelete->delete();

        toast('Your data as been deleted!', 'success');
        return redirect()->route('currency.view');
    }

    public function currencyStatus($id)
    {
        $currencyData = MasterCurrency::find($id);

        if ($currencyData) {
            if ($currencyData->cy_status) {
                $currencyData->cy_status = 0;
            } else {
                $currencyData->cy_status = 1;
            }
            $currencyData->save();
        }
        return back();
    }
}
