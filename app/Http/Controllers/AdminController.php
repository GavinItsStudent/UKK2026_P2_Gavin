<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\User;
use App\Models\AreaParkir;
use App\Models\Kendaraan;
use App\Models\LogAktivitas;
use App\Models\Shift;
use App\Models\Tarif;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

        $parkirAktif = Transaksi::where('status', 'masuk')->count();

        $totalTransaksi = Transaksi::whereDate('created_at', $today)->count();

        $pendapatanHariIni = Transaksi::whereDate('created_at', $today)
            ->sum('biaya_bayar');

        $areas = AreaParkir::all();

        $logs = LogAktivitas::with('user')
            ->latest('waktu_aktivitas')
            ->take(5)
            ->get();

        return view('main.admin', compact(
            'parkirAktif',
            'totalTransaksi',
            'pendapatanHariIni',
            'areas',
            'logs'
        ));
    }

    public function users()
    {
        $users = User::where('role', '!=', 'admin')
            ->oldest()
            ->get();

        return view('admin.user', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'username' => 'required|min:3|max:50|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|min:6|max:20',
            'role' => 'required|in:petugas,owner'
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status_aktif' => 0
        ]);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menambahkan user: ' . $user->username,
            'waktu_aktivitas' => now()
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|max:20',
            'role' => 'required|in:petugas,owner'
        ]);

        if (
            $request->username == $user->username &&
            $request->email == $user->email &&
            $request->role == $user->role &&
            !$request->filled('password')
        ) {
            return back()->with('error', 'Tidak ada perubahan data');
        }

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Mengedit user: ' . $user->username,
            'waktu_aktivitas' => now()
        ]);

        return back()->with('success', 'User berhasil diupdate');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->role == 'admin') {
            return back()->with('error', 'Admin tidak bisa dihapus');
        }

        $nama = $user->username;
        $user->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menghapus user: ' . $nama,
            'waktu_aktivitas' => now()
        ]);

        return back()->with('success', 'User berhasil dihapus');
    }

    public function shiftUser()
    {
        $shifts = Shift::latest()->get();

        return view('admin.shift', compact('shifts'));
    }

    public function storeShift(Request $request)
    {
        $request->validate([
            'nama_shift' => 'required|max:50|unique:shifts,nama_shift',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i|after:jam_masuk'
        ]);

        $shift = Shift::create([
            'nama_shift' => $request->nama_shift,
            'jam_masuk' => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar
        ]);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menambahkan shift: ' . $shift->nama_shift,
            'waktu_aktivitas' => now()
        ]);

        return back()->with('success', 'Shift berhasil ditambahkan');
    }

    public function updateShift(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        $request->validate([
            'nama_shift' => 'required|max:50',
            'jam_masuk' => 'required',
            'jam_keluar' => 'required'
        ]);

        if (
            $request->nama_shift == $shift->nama_shift &&
            $request->jam_masuk == $shift->jam_masuk &&
            $request->jam_keluar == $shift->jam_keluar
        ) {
            return back()->with('error', 'Tidak ada perubahan data');
        }

        $shift->update([
            'nama_shift' => $request->nama_shift,
            'jam_masuk' => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar
        ]);

        return back()->with('success', 'Shift berhasil diupdate');
    }

    public function destroyShift($id)
    {
        $shift = Shift::findOrFail($id);

        $nama = $shift->nama_shift;
        $shift->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menghapus shift: ' . $nama,
            'waktu_aktivitas' => now()
        ]);

        return back()->with('success', 'Shift berhasil dihapus');
    }

    public function tarif()
    {
        $tarif = Tarif::latest()->get();
        return view('admin.tarif', compact('tarif'));
    }

    public function storeTarif(Request $request)
    {
        $harga = str_replace(['Rp', '.', ' '], '', $request->tarif_per_jam);

        $request->merge([
            'tarif_per_jam' => $harga
        ]);

        $request->validate([
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'tarif_per_jam' => 'required|numeric|min:0'
        ]);

        Tarif::create([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tarif_per_jam' => $request->tarif_per_jam
        ]);

        return back()->with('success', 'Tarif berhasil ditambahkan');
    }
    public function updateTarif(Request $request, $id)
    {
        $tarif = Tarif::findOrFail($id);

        $harga = str_replace(['Rp', '.', ' '], '', $request->tarif_per_jam);

        $request->merge([
            'tarif_per_jam' => $harga
        ]);

        $request->validate([
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'tarif_per_jam' => 'required|numeric|min:0'
        ]);

        // 🚨 CEK ADA PERUBAHAN ATAU TIDAK
        if (
            $tarif->jenis_kendaraan == $request->jenis_kendaraan &&
            $tarif->tarif_per_jam == $request->tarif_per_jam
        ) {
            return back()->with('info', 'Tidak ada perubahan data');
        }

        $tarif->update([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tarif_per_jam' => $request->tarif_per_jam
        ]);

        return back()->with('success', 'Tarif berhasil diupdate');
    }

    public function destroyTarif($id)
    {
        $tarif = Tarif::findOrFail($id);

        $nama = $tarif->jenis_kendaraan;
        $tarif->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menghapus tarif: ' . $nama,
            'waktu_aktivitas' => now()
        ]);

        return back()->with('success', 'Tarif berhasil dihapus');
    }

    public function area()
    {
        $areas = AreaParkir::orderBy('id', 'asc')->get();
        return view('admin.area', compact('areas'));
    }

    public function storeArea(Request $request)
    {
        $request->validate([
            'nama_area' => 'required|max:100',
            'kapasitas' => 'required|integer|min:1'
        ]);

        $area = AreaParkir::create([
            'nama_area' => $request->nama_area,
            'kapasitas' => $request->kapasitas,
            'terisi' => 0
        ]);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menambahkan area: ' . $area->nama_area,
            'waktu_aktivitas' => now()
        ]);

        return back()->with('success', 'Area berhasil ditambahkan');
    }

    public function updateArea(Request $request, $id)
    {
        $area = AreaParkir::findOrFail($id);

        $request->validate([
            'nama_area' => 'required|max:100',
            'kapasitas' => 'required|integer|min:1'
        ]);


        if (
            $request->nama_area == $area->nama_area &&
            $request->kapasitas == $area->kapasitas
        ) {
            return back()->with('info', 'Tidak ada perubahan data');
        }

        $area->update([
            'nama_area' => $request->nama_area,
            'kapasitas' => $request->kapasitas
        ]);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Mengedit area: ' . $area->nama_area,
            'waktu_aktivitas' => now()
        ]);

        return back()->with('success', 'Area berhasil diupdate');
    }

    public function destroyArea($id)
    {
        $area = AreaParkir::findOrFail($id);

        $nama = $area->nama_area;
        $area->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menghapus area: ' . $nama,
            'waktu_aktivitas' => now()
        ]);

        return back()->with('success', 'Area berhasil dihapus');
    }

    public function kendaraan()
    {
        $kendaraans = Kendaraan::latest()->get();
        return view('admin.kendaraan', compact('kendaraans'));
    }

    public function storeKendaraan(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|max:15',
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'warna' => 'required|max:20'
        
        ]);

        Kendaraan::create([
            'plat_nomor' => $request->plat_nomor,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'warna' => $request->warna,
            'user_id' => auth()->id()
        ]);

        return back()->with('success', 'Kendaraan berhasil ditambahkan');
    }

    public function destroyKendaraan($id)
    {
        $k = Kendaraan::findOrFail($id);
        $k->delete();

        return back()->with('success', 'Kendaraan berhasil dihapus');
    }

    public function log()
    {
        $logs = LogAktivitas::with('user')
            ->latest('waktu_aktivitas')
            ->get();

        return view('admin.log', compact('logs'));
    }
}
