<?php

use App\Http\Controllers\Admin\ListVehicleController;
use App\Http\Controllers\Admin\UserController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\ParkingController;
use App\Http\Controllers\User\UploadDocumentController;
use App\Http\Controllers\User\VehicleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ParkingController::class, 'displayDashboard'])->name('dashboard');
    Route::get('/info-parking', [ParkingController::class, 'getInfoParking'])->name('info-parking');
    Route::get('/info-vehicle', [VehicleController::class, 'getInfoVehicle'])->name('info-vehicle');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['auth', 'verified'],
], function () {
    Route::resource('/vehicles', ListVehicleController::class);
    Route::get('/users', [UserController::class, 'index'])->name('user.list');
    Route::get('/permissions', [UserController::class, 'permission'])->name('permission.list');
    Route::get('/roles', [UserController::class, 'role'])->name('roles.list');
    Route::post('/verifydocument/{id}', [UploadDocumentController::class, 'verify'])->name('document.verify');
    Route::patch('/update-permission/{userID}', [UserController::class, 'changePermission'])->name('change.permission');
    Route::patch('/edit-permission/{id}', [UserController::class, 'editPermission'])->name('edit.permission');

});

Route::group([
    'prefix' => 'user',
    'as' => 'user.',
    'middleware' => ['auth', 'verified'],
], function () {
    Route::get('/uploaddocument', [UploadDocumentController::class, 'upload'])->name('upload.index');
    Route::post('/upload', [UploadDocumentController::class, 'store'])->name('upload.store');
    Route::delete('/delete/{id}', [UploadDocumentController::class, 'destroy'])->name('document.delete');
});

require __DIR__ . '/auth.php';
