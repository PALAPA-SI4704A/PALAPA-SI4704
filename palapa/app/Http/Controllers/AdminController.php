<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\Penugasan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Tampilkan halaman utama dashboard admin
     */
    public function index(Request $request)
    {
        // 1. Pengecekan Akses (Hanya Admin)
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // 2. Query Laporan Masuk
        $query = Report::with(['pelapor', 'penugasans.petugas'])->latest('report_id');

        // Filter Tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Pencarian Lokasi/Judul/Deskripsi
        if ($request->filled('location')) {
            $location = $request->location;
            $query->where(function ($q) use ($location) {
                $q->where('latitude', 'like', "%{$location}%")
                  ->orWhere('longitude', 'like', "%{$location}%")
                  ->orWhere('title', 'like', "%{$location}%")
                  ->orWhere('description', 'like', "%{$location}%")
                  ->orWhere('address', 'like', "%{$location}%");
            });
        }

        $laporanMasuk = $query->get();

        // 3. Menghitung Data Statistik
        $today = Carbon::today();
        $totalLaporan = Report::count();
        $laporanHariIni = Report::whereDate('created_at', $today)->count();
        $menungguVerifikasi = Report::where('status', 'pending')->count();
        $laporanValid = Report::where('status', 'valid')->count();
        $sedangDitangani = Report::where('status', 'diproses')->count();
        $laporanSelesai = Report::where('status', 'selesai')->count();
        $laporanDitolak = Report::where('status', 'ditolak')->count();

        // 4. Data untuk Line Chart Laporan Karhutla per periode
        $period = $request->input('period', '7days');
        $chartLabels = [];
        $chartCounts = [];
        
        if ($period === '30days') {
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dateStr = $date->format('Y-m-d');
                $label = $date->locale('id')->isoFormat('D MMM');
                $chartLabels[] = $label;
                $chartCounts[$dateStr] = 0;
            }
            
            $dbChartData = Report::select(DB::raw('DATE(created_at) as date_only'), DB::raw('count(*) as count'))
                ->where('created_at', '>=', Carbon::now()->subDays(29)->startOfDay())
                ->groupBy('date_only')
                ->get();

            foreach ($dbChartData as $data) {
                $dateKey = $data->date_only;
                if (isset($chartCounts[$dateKey])) {
                    $chartCounts[$dateKey] = $data->count;
                }
            }
        } elseif ($period === 'month') {
            $daysInMonth = Carbon::now()->daysInMonth;
            $startOfMonth = Carbon::now()->startOfMonth();
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::now()->day($day);
                $dateStr = $date->format('Y-m-d');
                $label = $date->locale('id')->isoFormat('D MMM');
                $chartLabels[] = $label;
                $chartCounts[$dateStr] = 0;
            }
            
            $dbChartData = Report::select(DB::raw('DATE(created_at) as date_only'), DB::raw('count(*) as count'))
                ->where('created_at', '>=', $startOfMonth)
                ->groupBy('date_only')
                ->get();

            foreach ($dbChartData as $data) {
                $dateKey = $data->date_only;
                if (isset($chartCounts[$dateKey])) {
                    $chartCounts[$dateKey] = $data->count;
                }
            }
        } elseif ($period === 'year') {
            $startOfYear = Carbon::now()->startOfYear();
            for ($m = 1; $m <= 12; $m++) {
                $date = Carbon::now()->month($m);
                $monthStr = $date->format('Y-m');
                $label = $date->locale('id')->isoFormat('MMMM');
                $chartLabels[] = $label;
                $chartCounts[$monthStr] = 0;
            }
            
            $dbChartData = Report::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_only"), DB::raw('count(*) as count'))
                ->where('created_at', '>=', $startOfYear)
                ->groupBy('month_only')
                ->get();

            foreach ($dbChartData as $data) {
                $monthKey = $data->month_only;
                if (isset($chartCounts[$monthKey])) {
                    $chartCounts[$monthKey] = $data->count;
                }
            }
        } else {
            // Default 7days
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dateStr = $date->format('Y-m-d');
                
                if ($i === 0) {
                    $label = 'Hari Ini';
                } elseif ($i === 1) {
                    $label = 'Kemarin';
                } else {
                    $label = $date->locale('id')->isoFormat('D MMMM');
                }
                
                $chartLabels[] = $label;
                $chartCounts[$dateStr] = 0;
            }

            $dbChartData = Report::select(DB::raw('DATE(created_at) as date_only'), DB::raw('count(*) as count'))
                ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
                ->groupBy('date_only')
                ->get();

            foreach ($dbChartData as $data) {
                $dateKey = $data->date_only;
                if (isset($chartCounts[$dateKey])) {
                    $chartCounts[$dateKey] = $data->count;
                }
            }
        }
        
        $chartDataValues = array_values($chartCounts);

        return view('admin.dashboard', compact(
            'laporanMasuk',
            'totalLaporan',
            'laporanHariIni',
            'menungguVerifikasi',
            'laporanValid',
            'sedangDitangani',
            'laporanSelesai',
            'laporanDitolak',
            'chartLabels',
            'chartDataValues'
        ));
    }

    /**
     * Tampilkan detail laporan untuk Admin
     */
    public function show(Report $report)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $report->load(['pelapor', 'penugasans.petugas']);
        $petugasTersedia = User::where('role', 'petugas')->get();

        return view('admin.reports.show', compact('report', 'petugasTersedia'));
    }

    /**
     * Verifikasi laporan (Validasi atau Tolak)
     */
    public function verify(Request $request, Report $report)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        Log::info('Admin verifying report', ['report_id' => $report->report_id, 'status' => $request->status, 'reason' => $request->rejection_reason]);

        $request->validate([
            'status' => 'required|in:valid,ditolak',
            'rejection_reason' => 'required_if:status,ditolak|string|max:500'
        ]);

        $data = ['status' => $request->status];
        if ($request->status === 'ditolak') {
            $data['rejection_reason'] = $request->rejection_reason;
        }

        $report->update($data);
        
        Log::info('Admin report verified successfully', ['new_status' => $report->status]);

        return redirect()->back()->with('success', 'Laporan berhasil diverifikasi menjadi: ' . ucfirst($request->status));
    }

    /**
     * Tugaskan petugas lapangan ke laporan
     */
    public function assign(Report $report, User $petugas)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Pastikan role user yang ditugaskan adalah petugas
        if ($petugas->role !== 'petugas') {
            return redirect()->back()->withErrors(['error' => 'User yang ditunjuk bukan merupakan Petugas Lapangan.']);
        }

        // Simpan ke tabel penugasan
        $report->penugasans()->create([
            'petugas_id' => $petugas->users_id,
            'assigned_at' => now()
        ]);

        // Perbarui status laporan menjadi diproses
        $report->update(['status' => 'diproses']);

        return redirect()->back()->with('success', 'Petugas ' . $petugas->users_name . ' berhasil ditugaskan.');
    }

    /**
     * Tampilkan daftar semua pengguna
     */
    public function usersIndex(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Tentukan role aktif (petugas atau masyarakat)
        $activeRole = $request->input('role', 'petugas');
        if (!in_array($activeRole, ['petugas', 'masyarakat'])) {
            $activeRole = 'petugas';
        }

        $query = User::where('role', $activeRole)->latest('users_id');

        // Pencarian berdasarkan nama, email, telepon
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('users_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'activeRole'));
    }

    /**
     * Tampilkan form edit pengguna
     */
    public function usersEdit(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Simpan perubahan data pengguna
     */
    public function usersUpdate(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $validated = $request->validate([
            'users_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->users_id . ',users_id',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:admin,petugas,masyarakat',
        ]);

        $user->users_name = $validated['users_name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->role = $validated['role'];

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna ' . $user->users_name . ' berhasil diperbarui.');
    }
}
