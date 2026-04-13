<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Kendaraan;
use App\Models\AreaParkir;
use App\Models\Tarif;
use Carbon\Carbon;

class PetugasController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

        $parkirAktif = Transaksi::where('status', 'masuk')->count();

        $totalHariIni = Transaksi::whereDate('created_at', $today)->count();

        $pendapatan = Transaksi::whereDate('created_at', $today)
            ->where('status', 'keluar')
            ->sum('biaya_bayar');

        $chart = Transaksi::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $labels = $chart->pluck('tanggal')->map(fn($d) => Carbon::parse($d)->format('d M'));
        $data = $chart->pluck('total');

        return view('Main.petugas', compact(
            'parkirAktif',
            'totalHariIni',
            'pendapatan',
            'labels',
            'data'
        ));
    }

    // ================= TRANSAKSI =================
    public function transaksi(Request $request)
    {
        $query = Transaksi::with(['kendaraan', 'area']);

        if ($request->search) {
            $query->whereHas('kendaraan', function ($q) use ($request) {
                $q->where('plat_nomor', 'like', '%' . $request->search . '%')
                    ->orWhere('warna', 'like', '%' . $request->search . '%')
                    ->orWhere('jenis_kendaraan', 'like', '%' . $request->search . '%');
            });
        }

        $transaksis = $query->oldest()->paginate(10);
        $areas = AreaParkir::all();

        return view('petugas.transaksi', compact('transaksis', 'areas'));
    }

    // ================= MASUK =================
    public function store(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|max:15',
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'warna' => 'required|max:20',
            'area_id' => 'required|exists:area_parkir,id'
        ]);

        $area = AreaParkir::findOrFail($request->area_id);

        if ($area->terisi >= $area->kapasitas) {
            return back()->with('error', 'Area parkir penuh!');
        }

        $kendaraan = Kendaraan::firstOrCreate(
            ['plat_nomor' => strtoupper($request->plat_nomor)],
            [
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'warna' => $request->warna,
                'user_id' => auth()->id()
            ]
        );

        $tarif = Tarif::where('jenis_kendaraan', $request->jenis_kendaraan)->first();

        Transaksi::create([
            'kendaraan_id' => $kendaraan->id,
            'user_id' => auth()->id(),
            'tarif_id' => $tarif->id ?? null,
            'area_id' => $request->area_id,
            'waktu_masuk' => now(),
            'status' => 'masuk',
            'biaya_bayar' => 0
        ]);

        $area->increment('terisi');

        return back()->with('success', 'Kendaraan masuk berhasil');
    }

    // ================= HITUNG BIAYA (BELUM BAYAR) =================
    public function keluar($id)
    {
        $trx = Transaksi::with(['kendaraan', 'area'])->findOrFail($id);

        if ($trx->status != 'masuk') {
            return back()->with('error', 'Transaksi tidak valid');
        }

        $waktuMasuk = Carbon::parse($trx->waktu_masuk);
        $waktuKeluar = now();

        $durasiMenit = $waktuMasuk->diffInMinutes($waktuKeluar);
        $durasiJam = ceil($durasiMenit / 60);
        if ($durasiJam < 1) $durasiJam = 1;

        $tarif = Tarif::where('jenis_kendaraan', $trx->kendaraan->jenis_kendaraan)->first();
        $biaya = $durasiJam * ($tarif->tarif_per_jam ?? 0);

        // ⛔ BELUM KELUAR → MASUK KE MODE BAYAR
        $trx->update([
            'waktu_keluar' => $waktuKeluar,
            'durasi_jam' => $durasiJam,
            'biaya_bayar' => $biaya,
            'status' => 'menunggu_pembayaran'
        ]);

        return back()->with('success', 'Silakan pilih metode pembayaran');
    }

    // ================= BAYAR CASH =================
    public function bayarCash($id)
    {
        $trx = Transaksi::with('area')->findOrFail($id);

        if ($trx->status != 'menunggu_pembayaran') {
            return back()->with('error', 'Belum masuk tahap pembayaran');
        }

        $trx->update([
            'status' => 'keluar',
            'metode_bayar' => 'cash'
        ]);

        if ($trx->area) {
            $trx->area->decrement('terisi');
        }

        return redirect()->route('petugas.transaksi.struk', $trx->id);
    }

    // ================= BAYAR QRIS =================
    public function bayarQris($id)
    {
        $trx = Transaksi::findOrFail($id);

        if ($trx->status != 'menunggu_pembayaran') {
            return back()->with('error', 'Belum masuk tahap pembayaran');
        }

        // NANTI DISINI BISA INTEGRASI MIDTRANS / DLL
        return view('petugas.qris', compact('trx'));
    }

    // ================= QRIS SUCCESS =================
    public function qrisSuccess($id)
    {
        $trx = Transaksi::with('area')->findOrFail($id);

        $trx->update([
            'status' => 'keluar',
            'metode_bayar' => 'qris'
        ]);

        if ($trx->area) {
            $trx->area->decrement('terisi');
        }

        return redirect()->route('petugas.transaksi.struk', $trx->id);
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        $trx = Transaksi::findOrFail($id);

        if ($trx->status == 'masuk' && $trx->area) {
            $trx->area->decrement('terisi');
        }

        $trx->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }

    // ================= STRUK =================
    public function strukMasuk($id)
    {
        $trx = Transaksi::with(['kendaraan', 'area'])->findOrFail($id);

        if ($trx->status == 'keluar') {
            return view('petugas.struk_klr', compact('trx'));
        }

        return view('petugas.struk_msk', compact('trx'));
    }
}
