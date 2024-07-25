<?php

use App\Http\Controllers\Backend\DataCompanyController;
use App\Http\Controllers\Backend\DataFastboatController;
use App\Http\Controllers\Backend\MasterIslandController;
use App\Http\Controllers\Backend\MasterPortController;
use App\Http\Controllers\BookingDataController;
use App\Http\Controllers\BookingTrashController;
use App\Http\Controllers\DestinyIslandController;
use App\Http\Controllers\DestinyPortController;
use App\Http\Controllers\MitraCompanyController;
use App\Http\Controllers\MitraFasboatController;
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
        Route::get('/company', [DataCompanyController::class, 'index'])->name('company.view');
        Route::get('/company/add', [DataCompanyController::class, 'add'])->name('company.add');
        Route::post('/company/store', [DataCompanyController::class, 'store'])->name('company.store');
        Route::get('/company/edit/{id}', [DataCompanyController::class, 'edit'])->name('company.edit');
        Route::post('/company/update/{id}', [DataCompanyController::class, 'update'])->name('company.update');
        Route::delete('/company/delete/{id}', [DataCompanyController::class, 'delete'])->name('company.delete');
        Route::get('/company/{id}', [DataCompanyController::class, 'show'])->name('company.show');
        Route::get('/company/emailstatus/{id}', [DataCompanyController::class, 'emailStatus'])->name('company.emailStatus');
        Route::get('/company/status/{id}', [DataCompanyController::class, 'companyStatus'])->name('company.status');

        // fast boat routes
        Route::get('/fastboat', [DataFastboatController::class, 'index'])->name('fastboat.view');
        Route::get('/fastboat/add', [DataFastboatController::class, 'add'])->name('fastboat.add');
        Route::post('/fastboat/store', [DataFastboatController::class, 'store'])->name('fastboat.store');
        Route::get('/fastboat/edit/{id}', [DataFastboatController::class, 'edit'])->name('fastboat.edit');
        Route::post('/fastboat/update/{id}', [DataFastboatController::class, 'update'])->name('fastboat.update');
        Route::delete('/fastboat/delete/{id}', [DataFastboatController::class, 'delete'])->name('fastboat.delete');
        Route::get('/fastboat/{id}', [DataFastboatController::class, 'show'])->name('fastboat.show');
        Route::get('/fastboat/status/{id}', [DataFastboatController::class, 'status'])->name('fastboat.status');

    });
    Route::prefix('master')->group(function () {
        // master island routes
        Route::get('/island', [MasterIslandController::class, 'index'])->name('island.view');
        Route::get('/island/add', [MasterIslandController::class, 'add'])->name('island.add');
        Route::post('/island/store', [MasterIslandController::class, 'store'])->name('island.store');
        Route::get('/island/edit/{id}', [MasterIslandController::class, 'edit'])->name('island.edit');
        Route::post('/island/update/{id}', [MasterIslandController::class, 'update'])->name('island.update');
        Route::delete('/island/delete/{id}', [MasterIslandController::class, 'delete'])->name('island.delete');
        Route::get('/island/{id}', [MasterIslandController::class, 'show'])->name('island.show');

        // master port routes
        Route::get('/port', [MasterPortController::class, 'index'])->name('port.view');
        Route::get('/port/add', [MasterPortController::class, 'add'])->name('port.add');
        Route::post('/port/store', [MasterPortController::class, 'store'])->name('port.store');
        Route::get('/port/edit/{id}', [MasterPortController::class, 'edit'])->name('port.edit');
        Route::post('/port/update/{id}', [MasterPortController::class, 'update'])->name('port.update');
        Route::delete('/port/delete/{id}', [MasterPortController::class, 'delete'])->name('port.delete');
        Route::get('/port/{id}', [MasterPortController::class, 'show'])->name('port.show');
    });
});
