<?php

use App\Http\Controllers\Api\AvailabilityApiController;
use App\Http\Controllers\Api\FastboatApiController;
use App\Http\Controllers\Api\PortApiController;
use App\Models\DataFastboat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//fastboat 
Route::get('fast-boat', [FastboatApiController::class, 'index']);
Route::get('fast-boat/en/{fb_slug_en}', [FastboatApiController::class, 'show_en']);
Route::get('fast-boat/idn/{fb_slug_idn}', [FastboatApiController::class, 'show_idn']);

// port
Route::get('port', [PortApiController::class, 'index']);
Route::get('port/en/{prt_slug_en}', [PortApiController::class, 'show_en']);
Route::get('port/idn/{prt_slug_idn}', [PortApiController::class, 'show_idn']);

// availability
Route::get('availability', [AvailabilityApiController::class, 'index']);

