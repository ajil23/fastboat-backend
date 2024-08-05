<?php

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
Route::get('fastboat', [FastboatApiController::class, 'index']);

// port
Route::get('port', [PortApiController::class, 'index']);
