<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

    public function updateKurs()
    {
        $currencies = MasterCurrency::all(); // Mengambil semua data kurs dari database
        $actor = auth()->user()->username; // Mengambil username dari sesi pengguna yang login

        foreach ($currencies as $currency) {
            // Mengambil data kurs dari API x-rates
            $response = Http::get("http://www.x-rates.com/calculator/", [
                'from' => $currency->cy_code,
                'to' => 'IDR',
                'amount' => 1
            ]);

            if ($response->successful()) {
                $htmlContent = $response->body();
                // Memproses HTML yang didapat dari x-rates
                $exp = explode('<span class="ccOutputRslt">', $htmlContent);
                $exp = explode('<span class="ccOutputTrail">', $exp[1]);
                $exp = explode('.', $exp[0]);

                $kurs_before = $currency->cry_rate;
                $kurs_asli = str_replace(',', '', $exp[0]);
                $kurs_round = round($kurs_asli, 0, PHP_ROUND_HALF_UP); // Membulatkan kurs
                $percentage = round($kurs_round * 8.8 / 100, 0, PHP_ROUND_HALF_UP);
                $kurs_plus = $kurs_round - $percentage; // Mengurangi kurs 8.8%

                // Update nilai kurs jika kurs_asli lebih dari 0, jika tidak gunakan kurs sebelumnya
                if ($kurs_asli > 0) {
                    $currency->update([
                        'cy_rate' => $kurs_plus,
                        'cy_updated_by' => $actor
                    ]);
                } else {
                    $currency->update([
                        'cy_rate' => $kurs_before,
                        'cy_updated_by' => $actor
                    ]);
                }
            } else {
                // Jika API gagal, tangani sesuai kebutuhan
                toast('Failed to fetch data', 'failed');

                return redirect()->route('admin.kurs');
            }
        }

        // Menampilkan pesan sukses atau gagal berdasarkan apakah ada baris yang diperbarui
        if ($currencies->isNotEmpty()) {
            toast('Successfully updated data! Please check your data.', 'success');
        } else {
            toast('Failed to update data! Please check your data.', 'failed');
        }

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
