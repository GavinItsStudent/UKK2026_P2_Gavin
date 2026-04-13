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
    ->controller(\App\Http\Controllers\AdminController::class)
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', 'dashboard')->name('dashboard');

        // USERS
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/store', [AdminController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        // SHIFT
        Route::get('/shift', 'shifts')->name('shifts');
        Route::post('/shift', 'storeShift')->name('shifts.store');
        Route::put('/shift/{id}', 'updateShift')->name('shifts.update');
        Route::delete('/shift/{id}', 'destroyShift')->name('shifts.destroy');

        // TARIF
        Route::get('/tarif', 'tarifs')->name('tarifs');
        Route::post('/tarif', 'storeTarif')->name('tarifs.store');
        Route::put('/tarif/{id}', 'updateTarif')->name('tarifs.update');
        Route::delete('/tarif/{id}', 'destroyTarif')->name('tarifs.destroy');

        // AREA
        Route::get('/area', 'areas')->name('area');
        Route::post('/area', 'storeArea')->name('areas.store');
        Route::put('/area/{id}', 'updateArea')->name('areas.update');
        Route::delete('/area/{id}', 'destroyArea')->name('areas.destroy');

        // KENDARAAN
        Route::get('/kendaraan', 'kendaraans')->name('kendaraan');
        Route::post('/kendaraan', 'storeKendaraan')->name('kendaraan.store');
        Route::put('/kendaraan/{id}', 'updateKendaraan')->name('kendaraan.update');
        Route::delete('/kendaraan/{id}', 'destroyKendaraan')->name('kendaraan.destroy');

        // LOG
        Route::get('/log', 'logs')->name('log');
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
