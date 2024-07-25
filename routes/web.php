<?php

use App\Http\Controllers\BookingDataController;
use App\Http\Controllers\BookingTrashController;
use App\Http\Controllers\DestinyIslandController;
use App\Http\Controllers\DestinyPortController;
use App\Http\Controllers\MitraCompanyController;
use App\Http\Controllers\MitraFasboatController;
use App\Http\Controllers\PartnerCompanyController;
use App\Http\Controllers\PartnerFastboatController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::prefix('data')->group(function () {
        // company routes
        Route::get('/company', [PartnerCompanyController::class, 'index'])->name('company.view');
        Route::get('/company/add', [PartnerCompanyController::class, 'add'])->name('company.add');
        Route::post('/company/store', [PartnerCompanyController::class, 'store'])->name('company.store');
        Route::get('/company/edit/{id}', [PartnerCompanyController::class, 'edit'])->name('company.edit');
        Route::post('/company/update/{id}', [PartnerCompanyController::class, 'update'])->name('company.update');
        Route::delete('/company/delete/{id}', [PartnerCompanyController::class, 'delete'])->name('company.delete');
        Route::get('/company/{id}', [PartnerCompanyController::class, 'show'])->name('company.show');
        Route::get('/company/{id}', [PartnerCompanyController::class, 'show'])->name('company.show');
        Route::get('/company/emailstatus/{id}', [PartnerCompanyController::class, 'emailStatus'])->name('company.emailStatus');
        Route::get('/company/status/{id}', [PartnerCompanyController::class, 'companyStatus'])->name('company.status');

        // fast boat routes
        Route::get('/fastboat', [PartnerFastboatController::class, 'index'])->name('fastboat.view');
        Route::get('/fastboat/add', [PartnerFastboatController::class, 'add'])->name('fastboat.add');
        Route::post('/fastboat/store', [PartnerFastboatController::class, 'store'])->name('fastboat.store');
        Route::get('/fastboat/edit/{id}', [PartnerFastboatController::class, 'edit'])->name('fastboat.edit');
        Route::post('/fastboat/update/{id}', [PartnerFastboatController::class, 'update'])->name('fastboat.update');
        Route::delete('/fastboat/delete/{id}', [PartnerFastboatController::class, 'delete'])->name('fastboat.delete');
        Route::get('/fastboat/{id}', [PartnerFastboatController::class, 'show'])->name('fastboat.show');
        Route::get('/fastboat/status/{id}', [PartnerFastboatController::class, 'status'])->name('fastboat.status');

    });
    Route::prefix('master')->group(function () {
        // master island routes
        Route::get('/island', [DestinyIslandController::class, 'index'])->name('island.view');
        Route::get('/island/add', [DestinyIslandController::class, 'add'])->name('island.add');
        Route::post('/island/store', [DestinyIslandController::class, 'store'])->name('island.store');
        Route::get('/island/edit/{id}', [DestinyIslandController::class, 'edit'])->name('island.edit');
        Route::post('/island/update/{id}', [DestinyIslandController::class, 'update'])->name('island.update');
        Route::delete('/island/delete/{id}', [DestinyIslandController::class, 'delete'])->name('island.delete');
        Route::get('/island/{id}', [DestinyIslandController::class, 'show'])->name('island.show');

        // master port routes
        Route::get('/port', [DestinyPortController::class, 'index'])->name('port.view');
        Route::get('/port/add', [DestinyPortController::class, 'add'])->name('port.add');
        Route::post('/port/store', [DestinyPortController::class, 'store'])->name('port.store');
        Route::get('/port/edit/{id}', [DestinyPortController::class, 'edit'])->name('port.edit');
        Route::post('/port/update/{id}', [DestinyPortController::class, 'update'])->name('port.update');
        Route::delete('/port/delete/{id}', [DestinyPortController::class, 'delete'])->name('port.delete');
        Route::get('/port/{id}', [DestinyPortController::class, 'show'])->name('port.show');
    });
});
