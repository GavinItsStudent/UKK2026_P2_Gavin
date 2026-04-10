<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PetugasController;
use Illuminate\Support\Facades\Route;

// ================= Auth & Profile (public) =================
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login_proses'])->name('login-proses');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    // ===== DASHBOARD =====
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    Route::get('/admin/users', [AdminController::class, 'users'])
        ->name('admin.users');

    Route::post('/admin/users', [AdminController::class, 'storeUser'])
        ->name('admin.users.store');

    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])
        ->name('admin.users.update');

    Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser'])
        ->name('admin.users.destroy');

    Route::get('/admin/shift', [AdminController::class, 'shiftUser'])
        ->name('admin.shift');

    Route::post('/admin/shift', [AdminController::class, 'storeShift'])
        ->name('admin.shift.store');

    Route::put('/admin/shift/{id}', [AdminController::class, 'updateShift'])
        ->name('admin.shift.update');

    Route::delete('/admin/shift/{id}', [AdminController::class, 'destroyShift'])
        ->name('admin.shift.destroy');

    Route::get('/admin/tarif', [AdminController::class, 'tarif'])
        ->name('admin.tarif');

    Route::post('/admin/tarif', [AdminController::class, 'storeTarif'])
        ->name('admin.tarif.store');

    Route::put('/admin/tarif/{id}', [AdminController::class, 'updateTarif'])
        ->name('admin.tarif.update');

    Route::delete('/admin/tarif/{id}', [AdminController::class, 'destroyTarif'])
        ->name('admin.tarif.destroy');

    Route::get('/admin/area', [AdminController::class, 'area'])
        ->name('admin.area');

    Route::post('/admin/area', [AdminController::class, 'storeArea'])
        ->name('admin.area.store');

    Route::put('/admin/area/{id}', [AdminController::class, 'updateArea'])
        ->name('admin.area.update');

    Route::delete('/admin/area/{id}', [AdminController::class, 'destroyArea'])
        ->name('admin.area.destroy');

    Route::get('/admin/kendaraan', [AdminController::class, 'kendaraan'])->name('admin.kendaraan');

    Route::post('/admin/kendaraan', [AdminController::class, 'storeKendaraan'])->name('admin.kendaraan.store');

    Route::put('/admin/kendaraan/{id}', [AdminController::class, 'updateKendaraan'])->name('admin.kendaraan.update');

    Route::delete('/admin/kendaraan/{id}', [AdminController::class, 'destroyKendaraan'])->name('admin.kendaraan.destroy');

    Route::get('/admin/log', [AdminController::class, 'log'])
        ->name('admin.log');
});

// ================= Petugas =================
Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/petugas/dashboard', [PetugasController::class, 'dashboard'])->name('petugas.dashboard');
    Route::get('/petugas/transaksi', [PetugasController::class, 'transaksi'])->name('petugas.transaksi');
});


Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
    Route::get('/owner/rekap', [OwnerController::class, 'rekap'])->name('owner.rekap');
});
