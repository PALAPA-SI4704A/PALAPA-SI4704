<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    public function index()
    {
        // 1. Pengecekan Akses (Hanya Petugas)
        if (Auth::user()->role !== 'petugas') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // 2. Mengambil SEMUA Laporan Masuk Langsung dari Tabel Reports
        // Diurutkan dari yang paling baru dibuat oleh warga
        $laporanMasuk = Report::with('pelapor')
                        ->orderBy('created_at', 'desc')
                        ->get();

        // 3. Menghitung Data Statistik berdasarkan seluruh laporan masuk
        $today = Carbon::today();
        
        $laporanHariIni = $laporanMasuk->filter(function ($report) use ($today) {
            return Carbon::parse($report->created_at)->isSameDay($today);
        })->count();

        // Asumsi status default saat warga buat laporan adalah 'menunggu' atau 'baru'
        // Sesuaikan nama status dengan yang ada di database Anda jika berbeda
        $diproses = $laporanMasuk->where('status', 'diproses')->count();
        $selesai = $laporanMasuk->where('status', 'selesai')->count();
        $total = $laporanMasuk->count();

        // 4. Mengambil Daftar Petugas Tersedia
        $petugasTersedia = User::where('role', 'petugas')->get();

        // 5. Mengirimkan seluruh data ke View
        return view('petugas.dashboard', compact(
            'laporanMasuk', 
            'petugasTersedia', 
            'laporanHariIni', 
            'diproses', 
            'selesai', 
            'total'
        ));
    }

    public function show($report)
    {
        // ... Logika untuk melihat detail laporan
    }

    public function assign($report, $petugas)
    {
        // ... Logika untuk menugaskan
    }

    public function verify(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:valid,palsu'
        ]);

        $report->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Laporan berhasil diverifikasi menjadi: ' . ucfirst($request->status));
    }
}
