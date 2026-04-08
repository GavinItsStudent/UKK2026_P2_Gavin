<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PetugasController;
use Illuminate\Support\Facades\Route;

// ================= Auth & Profile (public) =================
Route::get('/', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'login_proses'])->name('login-proses');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================= Admin =================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::get('/admin/tarif', [AdminController::class, 'tarif'])->name('admin.tarif');
    Route::get('/admin/area', [AdminController::class, 'area'])->name('admin.area');
    Route::get('/admin/log', [AdminController::class, 'log'])->name('admin.log');
});

// ================= Petugas =================
Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/petugas/dashboard', [PetugasController::class, 'dashboard'])->name('petugas.dashboard');
    Route::get('/petugas/transaksi', [PetugasController::class, 'transaksi'])->name('petugas.transaksi');
});

// ================= Owner =================
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
    Route::get('/owner/rekap', [OwnerController::class, 'rekap'])->name('owner.rekap');
});
