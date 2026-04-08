<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

        // Parkir aktif: kendaraan yang sedang masuk
        $parkirAktif = Transaksi::where('status', 'masuk')->count();

        // Total transaksi hari ini
        $totalTransaksi = Transaksi::whereDate('created_at', $today)->count();

        // Pendapatan hari ini
        $pendapatanHariIni = Transaksi::whereDate('created_at', $today)
            ->sum('biaya_bayar');

        // Ambil semua user
        $users = User::all();

        return view('main.admin', compact(
            'parkirAktif',
            'totalTransaksi',
            'pendapatanHariIni',
            'users'
        ));
    
    }

    public function users()
    {
        $users = User::all();

        return view('admin.user', compact('users'));
    }

    public function tarif()
    {
        return view('admin.tarif');
    }

    public function area()
    {
        return view('admin.area');
    }

    public function log()
    {
        return view('admin.log');
    }
}
