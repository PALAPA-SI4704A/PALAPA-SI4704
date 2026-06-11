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

        // 2. Mengambil Laporan HANYA yang di-assign ke Petugas yang sedang login
        // Diurutkan dari yang paling baru
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

        // 3. Menghitung Data Statistik berdasarkan laporan yang di-assign ke petugas ini
        $today = Carbon::today();
        
        $laporanHariIni = $laporanMasuk->filter(function ($report) use ($today) {
            return Carbon::parse($report->created_at)->isSameDay($today);
        })->count();

        // Asumsi status default saat warga buat laporan adalah 'menunggu' atau 'baru'
        $diproses = $laporanMasuk->where('status', 'diproses')->count();
        $selesai = $laporanMasuk->where('status', 'selesai')->count();
        $total = $laporanMasuk->count();

        // 4. Mengambil Daftar Petugas Tersedia
        return view('petugas.dashboard', compact(
            'laporanMasuk', 
            'laporanHariIni', 
            'diproses', 
            'selesai', 
            'total'
        ));

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

        // Cek apakah status saat ini tidak bisa diubah lagi
        if ($report->status === 'selesai' || $report->status === 'ditolak') {
            return redirect()->back()->with('error', 'Laporan dengan status ' . $this->getStatusLabel($report->status) . ' tidak dapat diubah lagi.');
        }

        // Initial validation
        $request->validate([
            'status' => 'required|string',
            'catatan' => 'nullable|string|max:1000',
            'bukti_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'bukti_foto.image' => 'File harus berupa gambar.',
            'bukti_foto.mimes' => 'Format gambar harus: jpeg, png, jpg, atau gif.',
            'bukti_foto.max' => 'Ukuran gambar tidak boleh lebih dari 2 MB.'
        ]);

        $newStatus = $request->input('status');
        $catatan = $request->input('catatan');
        $currentStatus = $report->status;
        $validTransitions = $this->getValidTransitions($currentStatus);

        // Validasi transisi status
        if (!in_array($newStatus, $validTransitions)) {
            return redirect()->back()->with('error', 'Transisi status dari ' . $this->getStatusLabel($currentStatus) . ' ke ' . $this->getStatusLabel($newStatus) . ' tidak diizinkan.');
        }

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $report, $newStatus, $catatan, $currentStatus) {
            // Validasi khusus untuk status "diproses" (in progress)
            if ($newStatus === 'diproses') {
                $request->validate([
                    'petugas_id' => 'required|exists:users,users_id|integer',
                    'catatan' => 'required|string|min:10|max:1000',
                ], [
                    'petugas_id.required' => 'Petugas harus ditugaskan saat mengubah status ke "In Progress".',
                    'petugas_id.exists' => 'Petugas yang dipilih tidak valid.',
                    'catatan.required' => 'Komentar penanganan harus diisi.',
                    'catatan.min' => 'Komentar penanganan minimal harus 10 karakter.',
                    'catatan.max' => 'Komentar penanganan maksimal 1000 karakter.',
                ]);

                $petugas_id = $request->input('petugas_id');

                // Cek apakah petugas ini sedang bertugas (On Duty)
                $isOnDuty = \App\Models\Penugasan::where('petugas_id', $petugas_id)
                    ->whereNull('completed_at')
                    ->exists();
                if ($isOnDuty) {
                    return redirect()->back()->withErrors(['error' => 'Petugas ini sedang bertugas (On Duty) dan tidak dapat ditugaskan kembali.']);
                }

                $report->update([
                    'status' => $newStatus,
                    'assigned_petugas_id' => $petugas_id,
                    'handling_note' => $catatan,
                ]);

                // Buat penugasan baru
                $report->penugasans()->create([
                    'petugas_id' => $petugas_id,
                    'assigned_at' => now()
                ]);
            } 
            // Validasi khusus untuk status "selesai" (resolved)
            elseif ($newStatus === 'selesai') {
                $request->validate([
                    'catatan' => 'required|string|min:10|max:1000',
                    'bukti_foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
                ], [
                    'catatan.required' => 'Komentar penanganan harus diisi.',
                    'catatan.min' => 'Komentar penanganan minimal harus 10 karakter.',
                    'catatan.max' => 'Komentar penanganan maksimal 1000 karakter.',
                    'bukti_foto.required' => 'Bukti foto penanganan harus diunggah.',
                    'bukti_foto.image' => 'File harus berupa gambar.',
                    'bukti_foto.mimes' => 'Format gambar harus: jpeg, png, jpg, atau gif.',
                    'bukti_foto.max' => 'Ukuran gambar tidak boleh lebih dari 2 MB.'
                ]);

                // Handle foto upload
                $bukti_foto_path = $report->bukti_foto;
                if ($request->hasFile('bukti_foto')) {
                    $file = $request->file('bukti_foto');
                    $finalName = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $bukti_foto_path = $file->storeAs('bukti_penanganan', $finalName, 'public');
                }

                $report->update([
                    'status' => $newStatus,
                    'handling_note' => $catatan,
                    'bukti_foto' => $bukti_foto_path,
                ]);

                // Update active penugasan record to mark officer as available
                \App\Models\Penugasan::where('report_id', $report->report_id)
                    ->whereNull('completed_at')
                    ->update([
                        'completed_at' => now(),
                        'bukti_photo' => $bukti_foto_path,
                    ]);
            }
            // Validasi khusus untuk status "ditolak" (invalid)
            elseif ($newStatus === 'ditolak') {
                $request->validate([
                    'catatan' => 'required|string|min:10|max:1000',
                ], [
                    'catatan.required' => 'Komentar penolakan harus diisi.',
                    'catatan.min' => 'Komentar penolakan minimal harus 10 karakter.',
                    'catatan.max' => 'Komentar penolakan maksimal 1000 karakter.',
                ]);

                $report->update([
                    'status' => $newStatus,
                    'handling_note' => $catatan,
                ]);

                // Update active penugasan record to mark officer as available
                \App\Models\Penugasan::where('report_id', $report->report_id)
                    ->whereNull('completed_at')
                    ->update([
                        'completed_at' => now(),
                    ]);
            }
            else {
                // Untuk status lain
                $report->update(['status' => $newStatus]);
            }

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