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
        
        if (Auth::user()->role !== 'petugas') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        
        
        $query = Report::with('pelapor')
            ->where('assigned_petugas_id', Auth::user()->users_id)
            ->orderBy('created_at', 'desc');

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

        
        $today = Carbon::today();
        
        $laporanHariIni = $laporanMasuk->filter(function ($report) use ($today) {
            return Carbon::parse($report->created_at)->isSameDay($today);
        })->count();

        
        $diproses = $laporanMasuk->where('status', 'diproses')->count();
        $selesai = $laporanMasuk->where('status', 'selesai')->count();
        $total = $laporanMasuk->count();

        
        return view('petugas.dashboard', compact(
            'laporanMasuk', 
            'laporanHariIni', 
            'diproses', 
            'selesai', 
            'total'
        ));

        
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

        if ($report->assigned_petugas_id !== Auth::user()->users_id) {
            abort(403, 'Anda tidak ditugaskan untuk menangani laporan ini.');
        }

        $report->load(['pelapor', 'statusHistories' => function ($query) {
            $query->orderBy('id', 'asc');
        }]);
        $petugasTersedia = User::where('role', 'petugas')->get();

        return view('petugas.reports.show', compact('report'));
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

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $report) {
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
        });
    }

    public function updateStatus(Request $request, Report $report)
    {
        if (Auth::user()->role !== 'petugas') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if ($report->assigned_petugas_id !== Auth::user()->users_id) {
            abort(403, 'Anda tidak ditugaskan untuk menangani laporan ini.');
        }

        $request->validate([
            'status' => 'required|string',
            'catatan' => 'nullable|string|max:1000',
            'bukti_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $report) {
            $currentStatus = $report->status;
            $newStatus = $request->input('status');
            $catatan = $request->input('catatan');

            if ($newStatus === 'diproses') {
                $request->validate(['petugas_id' => 'required|exists:users,users_id']);
                $report->penugasans()->create([
                    'petugas_id' => $request->petugas_id,
                    'assigned_at' => now()
                ]);
            } elseif ($newStatus === 'selesai') {
                $request->validate(['bukti_foto' => 'required|image']);
                $path = $request->file('bukti_foto')->store('bukti_penanganan', 'public');
                $report->penugasans()->whereNull('completed_at')->update([
                    'completed_at' => now(),
                    'bukti_photo' => $path
                ]);
            } elseif ($newStatus === 'ditolak') {
                $report->penugasans()->whereNull('completed_at')->update(['completed_at' => now()]);
            }

            $report->update(['status' => $newStatus]);
            $roleLabel = 'Petugas Pemadam';
            $statusMappingLabel = $this->getStatusLabel($newStatus);

            $report->statusHistories()->create([
                'status_awal' => $currentStatus,
                'status_baru' => $newStatus,
                'catatan' => $catatan ?: 'Status penanganan laporan diperbarui menjadi ' . $statusMappingLabel . ' oleh petugas.',
                'diubah_oleh' => Auth::user()->users_name . ' (' . $roleLabel . ')',
                'tanggal_ubah' => now(),
            ]);

            return redirect()->back()->with('success', 'Status laporan berhasil diperbarui menjadi: ' . $statusMappingLabel);
        });
    }

    /**
     * Get valid status transitions based on current status
     */
    private function getValidTransitions(string $currentStatus): array
    {
        return match($currentStatus) {
            'pending' => ['diproses', 'ditolak'],
            'valid' => ['diproses', 'ditolak'],
            'diproses' => ['selesai', 'ditolak'],
            default => []
        };
    }

    /**
     * Get human-readable status label
     */
    private function getStatusLabel(string $status): string
    {
        return match($status) {
            'pending' => 'Pending',
            'valid' => 'Verified',
            'ditolak' => 'Invalid',
            'diproses' => 'In Progress',
            'selesai' => 'Resolved',
            default => ucfirst($status)
        };
    }
}