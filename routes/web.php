<?php

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
        
        // mitra island routes
        Route::get('/island', [MitraIslandController::class, 'index'])->name('island.view');
        Route::get('/island/add', [MitraIslandController::class, 'add'])->name('island.add');
        Route::get('/island/store', [MitraIslandController::class, 'store'])->name('island.store');
        Route::get('/island/edit/{id}', [MitraIslandController::class, 'edit'])->name('island.edit');
        Route::get('/island/update/{id}', [MitraIslandController::class, 'update'])->name('island.update');
        Route::get('/island/delete/{id}', [MitraIslandController::class, 'delete'])->name('island.delete');

        // mitra port routes
        Route::get('/port', [MitraPortController::class, 'index'])->name('mitra.port');
        Route::get('/port/add', [MitraPortController::class, 'add'])->name('port.add');
        Route::get('/port/store', [MitraPortController::class, 'store'])->name('port.store');
        Route::get('/port/edit/{id}', [MitraPortController::class, 'edit'])->name('port.edit');
        Route::get('/port/update/{id}', [MitraPortController::class, 'update'])->name('port.update');
        Route::get('/port/delete/{id}', [MitraPortController::class, 'delete'])->name('port.delete');
    });
});
