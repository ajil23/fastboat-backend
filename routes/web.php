<?php

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

    Route::prefix('partner')->group(function () {
        // partner company routes
        Route::get('/company', [PartnerCompanyController::class, 'index'])->name('company.view');
        Route::get('/company/add', [PartnerCompanyController::class, 'add'])->name('company.add');
        Route::post('/company/store', [PartnerCompanyController::class, 'store'])->name('company.store');
        Route::get('/company/edit/{id}', [PartnerCompanyController::class, 'edit'])->name('company.edit');
        Route::get('/company/update/{id}', [PartnerCompanyController::class, 'update'])->name('company.update');
        Route::get('/company/delete/{id}', [PartnerCompanyController::class, 'delete'])->name('company.delete');
        Route::get('/company/{id}', [PartnerCompanyController::class, 'show'])->name('company.show');

        // partner fast boat routes
        Route::get('/fastboat', [PartnerFastboatController::class, 'index'])->name('fastboat.view');
        Route::get('/fastboat/add', [PartnerFastboatController::class, 'add'])->name('fastboat.add');
        Route::get('/fastboat/store', [PartnerFastboatController::class, 'store'])->name('fastboat.store');
        Route::get('/fastboat/edit/{id}', [PartnerFastboatController::class, 'edit'])->name('fastboat.edit');
        Route::get('/fastboat/update/{id}', [PartnerFastboatController::class, 'update'])->name('fastboat.update');
        Route::get('/fastboat/delete/{id}', [PartnerFastboatController::class, 'delete'])->name('fastboat.delete');
    });
    Route::prefix('destiny')->group(function () {
        // destiny island routes
        Route::get('/island', [DestinyIslandController::class, 'index'])->name('island.view');
        Route::get('/island/add', [DestinyIslandController::class, 'add'])->name('island.add');
        Route::post('/island/store', [DestinyIslandController::class, 'store'])->name('island.store');
        Route::get('/island/edit/{id}', [DestinyIslandController::class, 'edit'])->name('island.edit');
        Route::get('/island/update/{id}', [DestinyIslandController::class, 'update'])->name('island.update');
        Route::get('/island/delete/{id}', [DestinyIslandController::class, 'delete'])->name('island.delete');

        // destiny port routes
        Route::get('/port', [DestinyPortController::class, 'index'])->name('port.view');
        Route::get('/port/add', [DestinyPortController::class, 'add'])->name('port.add');
        Route::get('/port/store', [DestinyPortController::class, 'store'])->name('port.store');
        Route::get('/port/edit/{id}', [DestinyPortController::class, 'edit'])->name('port.edit');
        Route::get('/port/update/{id}', [DestinyPortController::class, 'update'])->name('port.update');
        Route::get('/port/delete/{id}', [DestinyPortController::class, 'delete'])->name('port.delete');
    });
});
