<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use Stripe\Service\Terminal\LocationService;

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

Route::controller(LocationController::class)->group(function () {
    Route::get('/provinces', 'getProvinces');
    Route::get('/districts/{provinceCode}', 'getDistricts');
    Route::get('/wards/{districtCode}', 'getWards'); 
    Route::get('/province-name/{provinceCode}', 'getProvinceName');
});
