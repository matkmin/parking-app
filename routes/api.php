<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\VehicleController;
use App\Http\Controllers\User\UploadDocumentController;

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

Route::middleware(['auth', 'verified'])->group(function () {

    Route::put('/vehicles/update-available/{id}', [VehicleController::class, 'updateAvailable']);

    Route::post('/vehicles/add-vehicle', [VehicleController::class, 'storeVehicle']);

    Route::get('/vehicles/search-vehicle', [VehicleController::class,'searchVehicles']);

    // Route::post('/verifydocument/{id}', [UploadDocumentController::class, 'verify'])->name('document.verify');


});
