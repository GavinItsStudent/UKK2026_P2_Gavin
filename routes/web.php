<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PetugasController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login_proses'])->name('login-proses');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->controller(AdminController::class)
    ->group(function () {

        Route::get('/dashboard', 'dashboard')->name('dashboard');

        Route::get('/users', 'users')->name('users');
        Route::post('/users', 'storeUser')->name('users.store');
        Route::put('/users/{id}', 'updateUser')->name('users.update');
        Route::delete('/users/{id}', 'destroyUser')->name('users.destroy');

        Route::get('/shift', 'shiftUser')->name('shift');
        Route::post('/shift', 'storeShift')->name('shift.store');
        Route::put('/shift/{id}', 'updateShift')->name('shift.update');
        Route::delete('/shift/{id}', 'destroyShift')->name('shift.destroy');

        Route::get('/tarif', 'tarif')->name('tarif');
        Route::post('/tarif', 'storeTarif')->name('tarif.store');
        Route::put('/tarif/{id}', 'updateTarif')->name('tarif.update');
        Route::delete('/tarif/{id}', 'destroyTarif')->name('tarif.destroy');

        Route::get('/area', 'area')->name('area');
        Route::post('/area', 'storeArea')->name('area.store');
        Route::put('/area/{id}', 'updateArea')->name('area.update');
        Route::delete('/area/{id}', 'destroyArea')->name('area.destroy');

        Route::get('/kendaraan', 'kendaraan')->name('kendaraan');
        Route::post('/kendaraan', 'storeKendaraan')->name('kendaraan.store');
        Route::put('/kendaraan/{id}', 'updateKendaraan')->name('kendaraan.update');
        Route::delete('/kendaraan/{id}', 'destroyKendaraan')->name('kendaraan.destroy');

        Route::get('/log', 'log')->name('log');
    });

Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->group(function () {

    Route::get('/dashboard', [PetugasController::class, 'dashboard'])
        ->name('petugas.dashboard');

    Route::get('/transaksi', [PetugasController::class, 'transaksi'])
        ->name('petugas.transaksi');

    Route::post('/transaksi', [PetugasController::class, 'store'])
        ->name('petugas.transaksi.store');

    Route::post('/transaksi/{id}/keluar', [PetugasController::class, 'keluar'])
        ->name('petugas.transaksi.keluar');

    Route::delete('/transaksi/{id}', [PetugasController::class, 'destroy'])
        ->name('petugas.transaksi.destroy');

    Route::get('/transaksi/{id}/print', [PetugasController::class, 'print'])
        ->name('petugas.transaksi.print');

    Route::get('/transaksi/struk/{id}', [PetugasController::class, 'strukMasuk'])
        ->name('petugas.transaksi.struk');

    Route::post('/transaksi/{id}/cash', [PetugasController::class, 'bayarCash'])
        ->name('petugas.transaksi.cash');

    Route::get('/transaksi/{id}/qris', [PetugasController::class, 'bayarQris'])
        ->name('petugas.transaksi.qris');

    Route::get('/transaksi/{id}/qris/success', [PetugasController::class, 'qrisSuccess'])
        ->name('petugas.transaksi.qris.success');
});

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
    Route::get('/owner/rekap', [OwnerController::class, 'rekap'])->name('owner.rekap');
});
