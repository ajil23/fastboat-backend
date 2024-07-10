<?php

use App\Http\Controllers\MitraCompanyController;
use App\Http\Controllers\MitraFasboatController;
use App\Http\Controllers\MitraIslandController;
use App\Http\Controllers\MitraPortController;
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

    Route::prefix('mitra')->group(function () {
        // mitra company routes
        Route::get('/company', [MitraCompanyController::class, 'index'])->name('company.view');
        Route::get('/company/add', [MitraCompanyController::class, 'add'])->name('company.add');
        Route::get('/company/store', [MitraCompanyController::class, 'store'])->name('company.store');
        Route::get('/company/edit/{id}', [MitraCompanyController::class, 'edit'])->name('company.edit');
        Route::get('/company/update/{id}', [MitraCompanyController::class, 'update'])->name('company.update');
        Route::get('/company/delete/{id}', [MitraCompanyController::class, 'delete'])->name('company.delete');

        // mitra fast boat routes
        Route::get('/fasboat', [MitraFasboatController::class, 'index'])->name('fastboat.view');
        Route::get('/fastboat/add', [MitraFasboatController::class, 'add'])->name('fastboat.add');
        Route::get('/fastboat/store', [MitraFasboatController::class, 'store'])->name('fastboat.store');
        Route::get('/fastboat/edit/{id}', [MitraFasboatController::class, 'edit'])->name('fastboat.edit');
        Route::get('/fastboat/update/{id}', [MitraFasboatController::class, 'update'])->name('fastboat.update');
        Route::get('/fastboat/delete/{id}', [MitraFasboatController::class, 'delete'])->name('fastboat.delete');

        // mitra island routes
        Route::get('/island', [MitraIslandController::class, 'index'])->name('island.view');
        Route::get('/island/add', [MitraIslandController::class, 'add'])->name('island.add');
        Route::get('/island/store', [MitraIslandController::class, 'store'])->name('island.store');
        Route::get('/island/edit/{id}', [MitraIslandController::class, 'edit'])->name('island.edit');
        Route::get('/island/update/{id}', [MitraIslandController::class, 'update'])->name('island.update');
        Route::get('/island/delete/{id}', [MitraIslandController::class, 'delete'])->name('island.delete');

        // mitra port routes
        Route::get('/port', [MitraPortController::class, 'index'])->name('port.view');
        Route::get('/port/add', [MitraPortController::class, 'add'])->name('port.add');
        Route::get('/port/store', [MitraPortController::class, 'store'])->name('port.store');
        Route::get('/port/edit/{id}', [MitraPortController::class, 'edit'])->name('port.edit');
        Route::get('/port/update/{id}', [MitraPortController::class, 'update'])->name('port.update');
        Route::get('/port/delete/{id}', [MitraPortController::class, 'delete'])->name('port.delete');
    });
});
