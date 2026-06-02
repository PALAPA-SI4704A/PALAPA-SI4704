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
        $query = Report::with('pelapor')->orderBy('created_at', 'desc');

        if (request()->filled('date')) {
            $query->whereDate('created_at', request('date'));
        }

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        if (request()->filled('location')) {
            $location = request('location');
            $query->where(function($q) use ($location) {
                $q->where('latitude', 'like', "%{$location}%")
                  ->orWhere('longitude', 'like', "%{$location}%")
                  ->orWhere('title', 'like', "%{$location}%")
                  ->orWhere('description', 'like', "%{$location}%");
            });
        }

        $laporanMasuk = $query->get();

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

    public function show(Report $report)
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $report->load('pelapor');
        $petugasTersedia = User::where('role', 'petugas')->get();

        return view('petugas.reports.show', compact('report', 'petugasTersedia'));
    }

    public function assign(Report $report, User $petugas)
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $report->penugasans()->create([
            'petugas_id' => $petugas->users_id,
            'assigned_at' => now()
        ]);

        $oldStatus = $report->status;
        $report->update(['status' => 'diproses']);

        $roleLabel = match (Auth::user()->role) {
            'masyarakat' => 'Pelapor',
            'petugas' => 'Admin Sistem',
            'admin' => 'Admin Sistem',
            default => ucfirst(Auth::user()->role)
        };

        $report->statusHistories()->create([
            'status_awal' => $oldStatus,
            'status_baru' => 'diproses',
            'catatan' => 'Laporan sedang diverifikasi oleh admin dan diteruskan ke petugas lapangan.',
            'diubah_oleh' => Auth::user()->users_name . ' (' . $roleLabel . ')',
            'tanggal_ubah' => now(),
        ]);

        return redirect()->back()->with('success', 'Petugas ' . $petugas->users_name . ' berhasil ditugaskan.');
    }

    public function verify(Request $request, Report $report)
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        \Log::info('Verifying report', ['report_id' => $report->report_id, 'status' => $request->status, 'reason' => $request->rejection_reason]);

        $request->validate([
            'status' => 'required|in:valid,ditolak',
            'rejection_reason' => 'required_if:status,ditolak|string|max:500'
        ]);

        $oldStatus = $report->status;

        $data = ['status' => $request->status];
        if ($request->status === 'ditolak') {
            $data['rejection_reason'] = $request->rejection_reason;
        }

        $report->update($data);
        
        \Log::info('Report verified successfully', ['new_status' => $report->status]);

        $roleLabel = match (Auth::user()->role) {
            'masyarakat' => 'Pelapor',
            'petugas' => 'Admin Sistem',
            'admin' => 'Admin Sistem',
            default => ucfirst(Auth::user()->role)
        };

        $catatan = $request->status === 'ditolak'
            ? 'Laporan ditolak. Alasan: ' . $request->rejection_reason
            : 'Laporan telah diverifikasi dan dinyatakan valid.';

        $report->statusHistories()->create([
            'status_awal' => $oldStatus,
            'status_baru' => $request->status,
            'catatan' => $catatan,
            'diubah_oleh' => Auth::user()->users_name . ' (' . $roleLabel . ')',
            'tanggal_ubah' => now(),
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil diverifikasi menjadi: ' . ucfirst($request->status));
    }

    public function updateStatus(Request $request, Report $report)
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $request->validate([
            'status' => 'required|in:pending,valid,ditolak,diproses,selesai',
            'catatan' => 'nullable|string|max:500'
        ]);

        $oldStatus = $report->status;
        $newStatus = $request->status;

        $report->update(['status' => $newStatus]);

        $roleLabel = 'Petugas Pemadam';

        $statusMappingLabel = match($newStatus) {
            'pending' => 'Pending',
            'valid' => 'Verified',
            'ditolak' => 'Invalid',
            'diproses' => 'In Progress',
            'selesai' => 'Resolved',
            default => ucfirst($newStatus)
        };

        $catatan = $request->catatan ?: 'Status penanganan laporan diperbarui menjadi ' . $statusMappingLabel . ' oleh petugas.';

        $report->statusHistories()->create([
            'status_awal' => $oldStatus,
            'status_baru' => $newStatus,
            'catatan' => $catatan,
            'diubah_oleh' => Auth::user()->users_name . ' (' . $roleLabel . ')',
            'tanggal_ubah' => now(),
        ]);

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui menjadi: ' . $statusMappingLabel);
    }
}
