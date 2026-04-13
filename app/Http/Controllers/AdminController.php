<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\User;
use App\Models\AreaParkir;
use App\Models\Kendaraan;
use App\Models\LogAktivitas;
use App\Models\Shift;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /* ================= DASHBOARD ================= */
    public function dashboard()
    {
        $today = now()->toDateString();

        $parkirAktif = Transaksi::where('status', 'masuk')->count();
        $totalTransaksi = Transaksi::whereDate('created_at', $today)->count();
        $pendapatanHariIni = Transaksi::whereDate('created_at', $today)->sum('biaya_bayar');

        $this->syncArea();

        $areas = AreaParkir::all();
        $totalKapasitas = AreaParkir::sum('kapasitas');
        $totalTerisi = AreaParkir::sum('terisi');

        $logs = LogAktivitas::with('user')->latest('waktu_aktivitas')->take(5)->get();

        $chart = Transaksi::selectRaw('DATE(created_at) as tanggal, SUM(biaya_bayar) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->limit(7)
            ->get();

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

    /* ================= SYNC AREA ================= */
    private function syncArea()
    {
        $areas = AreaParkir::all();

        foreach ($areas as $area) {
            $real = Kendaraan::where('area_id', $area->id)->count();
            if ($area->terisi != $real) {
                $area->update(['terisi' => $real]);
            }
        }
    }

    /* ================= USERS ================= */
    public function users()
    {
        $users = User::with('shift')
            ->where('role', '!=', 'admin')
            ->latest()
            ->get();

        $shifts = Shift::all();

        return view('admin.user', compact('users', 'shifts'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'shift_id' => $request->shift_id,
            'status_aktif' => 0
        ]);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Tambah user: ' . $user->username,
            'waktu_aktivitas' => now()
        ]);

        return back()->with('success', 'User ditambahkan');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'shift_id' => $request->shift_id
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'User diupdate');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->role == 'admin') {
            return back()->with('error', 'Admin tidak bisa dihapus');
        }

        $user->delete();

        return back()->with('success', 'User dihapus');
    }

    /* ================= SHIFT ================= */
    public function shiftUser()
    {
        $shifts = Shift::latest()->get();
        return view('admin.shift', compact('shifts'));
    }

    public function storeShift(Request $request)
    {
        Shift::create($request->all());
        return back()->with('success', 'Shift ditambahkan');
    }

    public function updateShift(Request $request, $id)
    {
        Shift::findOrFail($id)->update($request->all());
        return back()->with('success', 'Shift diupdate');
    }

    public function destroyShift($id)
    {
        Shift::findOrFail($id)->delete();
        return back()->with('success', 'Shift dihapus');
    }

    /* ================= TARIF ================= */
    public function tarif()
    {
        $tarif = Tarif::latest()->get();
        return view('admin.tarif', compact('tarif'));
    }

    public function storeTarif(Request $request)
    {
        Tarif::create($request->all());
        return back()->with('success', 'Tarif ditambahkan');
    }

    public function updateTarif(Request $request, $id)
    {
        Tarif::findOrFail($id)->update($request->all());
        return back()->with('success', 'Tarif diupdate');
    }

    public function destroyTarif($id)
    {
        Tarif::findOrFail($id)->delete();
        return back()->with('success', 'Tarif dihapus');
    }

    /* ================= AREA ================= */
    public function area()
    {
        $this->syncArea();
        $areas = AreaParkir::all();
        return view('admin.area', compact('areas'));
    }

    /* ================= KENDARAAN ================= */
    public function kendaraan(Request $request)
    {
        $this->syncArea();

        $query = Kendaraan::with(['area', 'user']);

        if ($request->search) {
            $query->where('plat_nomor', 'like', '%' . $request->search . '%');
        }

        $kendaraans = $query->latest()->paginate(10);

        $areas = AreaParkir::all()->map(function ($a) {
            $a->sisa = $a->kapasitas - $a->terisi;
            return $a;
        });

        return view('admin.kendaraan', compact('kendaraans', 'areas'));
    }

    public function storeKendaraan(Request $request)
    {
        DB::transaction(function () use ($request) {

            $area = AreaParkir::lockForUpdate()->find($request->area_id);

            if ($area->terisi >= $area->kapasitas) {
                throw new \Exception('Area penuh');
            }

            Kendaraan::create([
                'plat_nomor' => strtoupper($request->plat_nomor),
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'warna' => $request->warna,
                'area_id' => $request->area_id,
                'user_id' => auth()->id()
            ]);

            $area->increment('terisi');
        });

        return back()->with('success', 'Kendaraan ditambahkan');
    }

    public function updateKendaraan(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        DB::transaction(function () use ($request, $kendaraan) {

            $old = AreaParkir::lockForUpdate()->find($kendaraan->area_id);
            $new = AreaParkir::lockForUpdate()->find($request->area_id);

            if ($old->id != $new->id) {
                $old->decrement('terisi');
                $new->increment('terisi');
            }

            $kendaraan->update($request->all());
        });

        return back()->with('success', 'Kendaraan diupdate');
    }

    public function destroyKendaraan($id)
    {
        DB::transaction(function () use ($id) {

            $k = Kendaraan::findOrFail($id);

            if ($k->area_id) {
                AreaParkir::lockForUpdate()
                    ->find($k->area_id)
                    ->decrement('terisi');
            }

            $k->delete();
        });

        return back()->with('success', 'Kendaraan dihapus');
    }

    /* ================= LOG ================= */
    public function log()
    {
        $logs = LogAktivitas::with('user')->latest()->get();
        return view('admin.log', compact('logs'));
    }
}
