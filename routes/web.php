<?php

use App\Http\Controllers\Admin\PermissionController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\User\ParkingController;
use App\Http\Controllers\User\VehicleController;
use App\Http\Controllers\Admin\ListVehicleController;
use App\Http\Controllers\User\UploadDocumentController;

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
    Route::patch('edit-username/{userID}', [UserController::class, 'updateUserName'])->name('update.username');
    Route::patch('edit-useremail/{userID}', [UserController::class, 'updateUserEmail'])->name('update.useremail');
    Route::delete('/delete-user/{userID}', [UserController::class, 'deleteUser'])->name('delete.user');
    Route::get('/permissions', [PermissionController::class, 'permission'])->name('permission.list');
    Route::post('/update-permission/{roleID}', [PermissionController::class, 'changePermission'])->name('change.permission');
    Route::patch('/edit-permission/{id}', [PermissionController::class, 'editPermission'])->name('edit.permission');
    Route::delete('/delete-permission/{id}', [PermissionController::class, 'deletePermissionName'])->name('delete.permission.name');
    Route::get('/roles', [RoleController::class, 'role'])->name('roles.list');
    Route::post('/add-role', [RoleController::class, 'addRole'])->name('add.role');
    Route::post('/change-role/{userID}', [RoleController::class, 'changeRole'])->name('change.role');
    Route::delete('/delete-role/{roleID}', [RoleController::class, 'deleteRole'])->name('delete.role');
    Route::post('/verifydocument/{id}', [UploadDocumentController::class, 'verify'])->name('document.verify');
});

Route::group([
    'prefix' => 'user',
    'as' => 'user.',
    'middleware' => ['auth', 'verified'],
], function () {
    Route::get('/uploaddocument', [UploadDocumentController::class, 'uploadDocument'])->name('upload.index');
    Route::post('/upload', [UploadDocumentController::class, 'store'])->name('upload.store');
    Route::delete('/delete/{id}', [UploadDocumentController::class, 'destroy'])->name('document.delete');
});

require __DIR__ . '/auth.php';
