<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Transaksi;
use App\Models\User;
use App\Models\AreaParkir;
use App\Models\Kendaraan;
use App\Models\LogAktivitas;
use App\Models\Shift;
use App\Models\Tarif;
use Carbon\Carbon;

class AdminController extends Controller
{
    /* ================= DASHBOARD ================= */
    public function dashboard()
    {
        $today = now()->toDateString();
        $this->syncArea();

        $parkirAktif = Transaksi::whereIn('status', ['masuk', 'menunggu_pembayaran'])->count();
        $totalTransaksi = Transaksi::whereDate('created_at', $today)->count();
        $pendapatanHariIni = Transaksi::whereDate('created_at', $today)->sum('biaya_bayar');

        $areas = AreaParkir::all();
        $totalKapasitas = AreaParkir::sum('kapasitas');
        $totalTerisi = AreaParkir::sum('terisi');

        $logs = LogAktivitas::with('user')
            ->latest('waktu_aktivitas')
            ->take(5)
            ->get();

        // ================= CHART 7 HARI TANPA BOLONG =================
        $rawChart = Transaksi::selectRaw('DATE(created_at) as tanggal, SUM(biaya_bayar) as total')
            ->whereDate('created_at', '>=', now()->subDays(6))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $chart = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();

            $chart->push([
                'tanggal' => Carbon::parse($date)->translatedFormat('d M'),
                'total' => $rawChart[$date]->total ?? 0
            ]);
        }
        // =============================================================

        return view('Main.admin', compact(
            'parkirAktif',
            'totalTransaksi',
            'pendapatanHariIni',
            'areas',
            'logs',
            'totalKapasitas',
            'totalTerisi',
            'chart'
        ));
    }

    private function syncArea()
    {
        foreach (AreaParkir::all() as $area) {
            $real = Transaksi::where('area_id', $area->id)
                ->whereIn('status', ['masuk', 'menunggu_pembayaran'])
                ->count();

            $area->update(['terisi' => $real]);
        }
    }

    /* ================= USERS ================= */
    public function users()
    {
        $users = User::with('shift')
            ->where('role', '!=', 'admin')
            ->latest()
            ->paginate(10);

        $shifts = Shift::all();

        return view('admin.user', compact('users', 'shifts'));
    }

    public function storeUser(Request $r)
    {
        $r->validate([
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:petugas,owner',
            'shift_id' => 'nullable|exists:shifts,id'
        ]);

        User::create([
            'username' => $r->username,
            'email' => $r->email,
            'password' => Hash::make($r->password),
            'role' => $r->role,
            'shift_id' => $r->shift_id,
            'status_aktif' => 0
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    public function updateUser(Request $r, $id)
    {
        $r->validate([
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:petugas,owner',
            'shift_id' => 'nullable|exists:shifts,id'
        ]);

        $data = $r->only('username', 'email', 'role', 'shift_id');

        if ($r->filled('password')) {
            $data['password'] = Hash::make($r->password);
        }

        User::findOrFail($id)->update($data);

        return back()->with('success', 'User berhasil diupdate');
    }

    public function destroyUser($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User berhasil dihapus');
    }

    /* ================= SHIFT ================= */
    public function shifts()
    {
        $shifts = Shift::latest()->paginate(10);
        return view('admin.shift', compact('shifts'));
    }

    public function storeShift(Request $r)
    {
        $r->validate([
            'nama_shift' => 'required',
            'jam_masuk' => 'required',
            'jam_keluar' => 'required',
        ]);

        Shift::create($r->only('nama_shift', 'jam_masuk', 'jam_keluar'));

        return back()->with('success', 'Shift berhasil ditambahkan');
    }

    public function updateShift(Request $r, $id)
    {
        $r->validate([
            'nama_shift' => 'required',
            'jam_masuk' => 'required',
            'jam_keluar' => 'required',
        ]);

        Shift::findOrFail($id)->update($r->only('nama_shift', 'jam_masuk', 'jam_keluar'));

        return back()->with('success', 'Shift berhasil diupdate');
    }

    public function destroyShift($id)
    {
        Shift::findOrFail($id)->delete();
        return back()->with('success', 'Shift berhasil dihapus');
    }

    /* ================= TARIF ================= */
    public function tarifs()
    {
        $tarif = Tarif::latest()->paginate(10);
        return view('admin.tarif', compact('tarif'));
    }

    public function storeTarif(Request $r)
    {
        $r->validate([
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'tarif_per_jam' => 'required|numeric'
        ]);

        Tarif::create($r->only('jenis_kendaraan', 'tarif_per_jam'));

        return back()->with('success', 'Tarif berhasil ditambahkan');
    }

    public function updateTarif(Request $r, $id)
    {
        $r->validate([
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'tarif_per_jam' => 'required|numeric'
        ]);

        Tarif::findOrFail($id)->update($r->only('jenis_kendaraan', 'tarif_per_jam'));

        return back()->with('success', 'Tarif berhasil diupdate');
    }


    public function destroyTarif($id)
    {
        Tarif::findOrFail($id)->delete();
        return back()->with('success', 'Tarif berhasil dihapus');
    }

    public function areas()
    {
        $areas = AreaParkir::latest()->get();

        return view('admin.area', compact('areas'));
    }

    public function storeArea(Request $r)
    {
        $r->validate([
            'nama_area' => 'required|string|max:100',
            'kapasitas' => 'required|integer|min:1',
        ]);

        AreaParkir::create([
            'nama_area' => $r->nama_area,
            'kapasitas' => $r->kapasitas,
            'terisi' => 0,
        ]);

        return back()->with('success', 'Area parkir berhasil ditambahkan');
    }

    public function destroyArea($id)
    {
        $area = AreaParkir::findOrFail($id);
        $area->delete();

        return back()->with('success', 'Area parkir berhasil dihapus');
    }

    public function updateArea(Request $r, $id)
    {
        $r->validate([
            'nama_area' => 'required|string|max:100',
            'kapasitas' => 'required|numeric|min:1',
        ]);

        $area = AreaParkir::findOrFail($id);

        $area->update([
            'nama_area' => $r->nama_area,
            'kapasitas' => $r->kapasitas,
        ]);

        return back()->with('success', 'Area parkir berhasil diupdate');
    }

    public function kendaraans()
    {
        $kendaraans = Kendaraan::with('area')->latest()->paginate(10);

        $areas = AreaParkir::all();

        return view('admin.kendaraan', compact('kendaraans', 'areas'));
    }

    public function storeKendaraan(Request $r)
    {
        $r->validate([
            'plat_nomor' => 'required|unique:kendaraan,plat_nomor', // 🔥 FIX INI
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'warna' => 'required',
            'area_id' => 'required|exists:area_parkir,id',
        ]);

        $area = AreaParkir::findOrFail($r->area_id);

        // cek kapasitas
        if ($area->terisi >= $area->kapasitas) {
            return back()->with('error', 'Area parkir penuh');
        }

        Kendaraan::create([
            'plat_nomor' => $r->plat_nomor,
            'jenis_kendaraan' => $r->jenis_kendaraan,
            'warna' => $r->warna,
            'area_id' => $r->area_id,
            'user_id' => auth()->id(), // 🔥 FIX
        ]);

        // update terisi area
        $area->increment('terisi');

        return back()->with('success', 'Kendaraan berhasil ditambahkan');
    }

    public function updateKendaraan(Request $r, $id)
    {
        $r->validate([
            'plat_nomor' => 'required|unique:kendaraan,plat_nomor,' . $id,
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'warna' => 'required',
            'area_id' => 'required|exists:area_parkir,id',
        ]);

        $kendaraan = Kendaraan::findOrFail($id);

        $kendaraan->update([
            'plat_nomor' => $r->plat_nomor,
            'jenis_kendaraan' => $r->jenis_kendaraan,
            'warna' => $r->warna,
            'area_id' => $r->area_id,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Kendaraan berhasil diupdate');
    }

    public function destroyKendaraan($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        // opsional: kurangi terisi area
        if ($kendaraan->area_id) {
            AreaParkir::find($kendaraan->area_id)?->decrement('terisi');
        }

        $kendaraan->delete();

        return back()->with('success', 'Kendaraan berhasil dihapus');
    }

    public function logs()
    {
        $logs = LogAktivitas::with('user')
            ->latest('waktu_aktivitas')
            ->paginate(10);

        return view('admin.log', compact('logs'));
    }
}
