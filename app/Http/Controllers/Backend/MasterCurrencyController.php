<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function updateKurs($actor = null)
    {
        if (!$actor) {
            $actor = auth()->check() ? auth()->user()->name : 'Cronjob';
        }

        MasterCurrency::chunk(100, function ($currencies) use ($actor) {
            $dataToUpdate = [];

            $tableName = (new MasterCurrency)->getTable();
            // $ids = implode(',', array_column($dataToUpdate, 'cy_id'));
            $casesRate = '';
            $casesUpdatedBy = '';
            $casesUpdatedAt = '';
            $id = array();
            // dd($currencies);
            foreach ($currencies as $currency) {
                // Proses pengambilan kurs dan update data seperti sebelumnya
                $response = Http::get("http://www.x-rates.com/calculator/", [
                    'from' => $currency->cy_code,
                    'to' => 'IDR',
                    'amount' => 1
                ]);

                if ($response->successful()) {
                    $htmlContent = $response->body();
                    $exp = explode('<span class="ccOutputRslt">', $htmlContent);
                    $exp = explode('<span class="ccOutputTrail">', $exp[1]);
                    $exp = explode('.', $exp[0]);

                    $kurs_before = $currency->cy_rate;
                    $kurs_asli = str_replace(',', '', $exp[0]);
                    $kurs_round = round($kurs_asli, 0, PHP_ROUND_HALF_UP);
                    $percentage = round($kurs_round * 8.8 / 100, 0, PHP_ROUND_HALF_UP);
                    $kurs_plus = $kurs_round - $percentage;

                    $newRate = $kurs_asli > 0 ? $kurs_plus : $kurs_before;

                    $casesRate .= "WHEN {$currency->cy_id} THEN '{$newRate}' ";
                    $casesUpdatedBy .= "WHEN {$currency->cy_id} THEN '{$actor}' ";
                    $casesUpdatedAt .= "WHEN {$currency->cy_id} THEN NOW() ";
                    $id[] = $currency->cy_id;
                }
            }
            $ids = implode(',', $id);
            $updateQuery = "UPDATE {$tableName} 
                            SET cy_rate = CASE cy_id {$casesRate} END, 
                                cy_updated_by = CASE cy_id {$casesUpdatedBy} END, 
                                updated_at = CASE cy_id {$casesUpdatedAt} END
                            WHERE cy_id IN ({$ids})";
            DB::statement($updateQuery);
        });

        toast('Successfully updated data!', 'success');
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
