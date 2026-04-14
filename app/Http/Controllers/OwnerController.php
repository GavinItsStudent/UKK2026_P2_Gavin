<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Models\LogAktivitas;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

        $pendapatanHariIni = Transaksi::whereDate('created_at', $today)
            ->where('status', 'keluar')
            ->sum('biaya_bayar');

        $totalTransaksi = Transaksi::whereDate('created_at', $today)->count();

        $parkirAktif = Transaksi::where('status', 'masuk')->count();

        $areas = AreaParkir::all();

        $totalKapasitas = $areas->sum('kapasitas');
        $totalTerisi = $areas->sum('terisi');

        $logs = LogAktivitas::with('user')
            ->orderBy('waktu_aktivitas', 'desc')
            ->limit(5)
            ->get();

        return view('Main.owner', compact(
            'pendapatanHariIni',
            'totalTransaksi',
            'parkirAktif',
            'areas',
            'totalKapasitas',
            'totalTerisi',
            'logs'
        ));
    }

    public function rekap(Request $request)
    {
        $dari = $request->dari;
        $sampai = $request->sampai;

        $query = \App\Models\Transaksi::with(['kendaraan', 'area']);

        if ($dari && $sampai) {
            $query->whereBetween('waktu_masuk', [$dari, $sampai]);
        }

        $parkir = (clone $query)
            ->orderBy('waktu_masuk', 'desc')
            ->paginate(10, ['*'], 'parkir_page');

        $transaksi = (clone $query)
            ->where('status', 'keluar')
            ->orderBy('waktu_keluar', 'desc')
            ->paginate(10, ['*'], 'transaksi_page');

        return view('Owner.rekap', compact('parkir', 'transaksi'));
    }
}
