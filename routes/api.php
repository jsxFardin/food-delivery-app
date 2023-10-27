<?php

use App\Http\Controllers\Rider\RiderLocationControler;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('store-rider-location', [RiderLocationControler::class, 'store'])->name('store.rider.location');
    Route::get('nearest-riders/{restaurantId}', [RiderLocationControler::class, 'getNearestRiders'])->name('nearest.riders');
});
