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
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // 2. Query Laporan Masuk (Default to unfinished reports on the dashboard if status is not filtered)
        $query = Report::with(['pelapor', 'penugasans.petugas']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereNotIn('status', ['selesai', 'ditolak']);
        }

        $query->latest('report_id');

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Filter Wilayah
        if ($request->filled('region')) {
            $region = $request->region;
            $query->where('address', 'like', "%{$region}%");
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
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

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $totalLaporan = Report::count();
        $laporanHariIni = Report::whereDate('created_at', $today)->count();
        $menungguVerifikasi = Report::where('status', 'pending')->count();
        $laporanValid = Report::where('status', 'valid')->count();
        $sedangDitangani = Report::where('status', 'diproses')->count();
        $laporanSelesai = Report::where('status', 'selesai')->count();
        $laporanDitolak = Report::where('status', 'ditolak')->count();

        // 4. Perhitungan Tren (Kenaikan/Penurunan) dibanding periode sebelumnya
        $period = $request->input('period', '7days');
        
        if ($period === '30days') {
            $startCurrent = Carbon::now()->subDays(29)->startOfDay();
            $endCurrent = Carbon::now()->endOfDay();
            $startPrev = Carbon::now()->subDays(59)->startOfDay();
            $endPrev = Carbon::now()->subDays(30)->endOfDay();
        } elseif ($period === 'month') {
            $startCurrent = Carbon::now()->startOfMonth();
            $endCurrent = Carbon::now()->endOfDay();
            $startPrev = Carbon::now()->subMonth()->startOfMonth();
            $endPrev = Carbon::now()->subMonth()->endOfMonth();
        } elseif ($period === 'year') {
            $startCurrent = Carbon::now()->startOfYear();
            $endCurrent = Carbon::now()->endOfDay();
            $startPrev = Carbon::now()->subYear()->startOfYear();
            $endPrev = Carbon::now()->subYear()->endOfYear();
        } else {
            // Default 7days
            $startCurrent = Carbon::now()->subDays(6)->startOfDay();
            $endCurrent = Carbon::now()->endOfDay();
            $startPrev = Carbon::now()->subDays(13)->startOfDay();
            $endPrev = Carbon::now()->subDays(7)->endOfDay();
        }

        // Laporan Hari Ini vs Kemarin
        $laporanKemarin = Report::whereDate('created_at', $yesterday)->count();
        $trendHariIni = $this->getTrendData($laporanHariIni, $laporanKemarin, true);

        // Menunggu Verifikasi
        $currPending = Report::where('status', 'pending')->whereBetween('created_at', [$startCurrent, $endCurrent])->count();
        $prevPending = Report::where('status', 'pending')->whereBetween('created_at', [$startPrev, $endPrev])->count();
        $trendMenungguVerifikasi = $this->getTrendData($currPending, $prevPending, true);

        // Sedang Ditangani (Diproses)
        $currDiproses = Report::where('status', 'diproses')->whereBetween('created_at', [$startCurrent, $endCurrent])->count();
        $prevDiproses = Report::where('status', 'diproses')->whereBetween('created_at', [$startPrev, $endPrev])->count();
        $trendSedangDitangani = $this->getTrendData($currDiproses, $prevDiproses, false);

        // Valid
        $currValid = Report::where('status', 'valid')->whereBetween('created_at', [$startCurrent, $endCurrent])->count();
        $prevValid = Report::where('status', 'valid')->whereBetween('created_at', [$startPrev, $endPrev])->count();
        $trendLaporanValid = $this->getTrendData($currValid, $prevValid, false);

        // Selesai
        $currSelesai = Report::where('status', 'selesai')->whereBetween('created_at', [$startCurrent, $endCurrent])->count();
        $prevSelesai = Report::where('status', 'selesai')->whereBetween('created_at', [$startPrev, $endPrev])->count();
        $trendLaporanSelesai = $this->getTrendData($currSelesai, $prevSelesai, false);

        // Ditolak
        $currDitolak = Report::where('status', 'ditolak')->whereBetween('created_at', [$startCurrent, $endCurrent])->count();
        $prevDitolak = Report::where('status', 'ditolak')->whereBetween('created_at', [$startPrev, $endPrev])->count();
        $trendLaporanDitolak = $this->getTrendData($currDitolak, $prevDitolak, true);

        // Total Laporan
        $currTotal = Report::whereBetween('created_at', [$startCurrent, $endCurrent])->count();
        $prevTotal = Report::whereBetween('created_at', [$startPrev, $endPrev])->count();
        $trendTotalLaporan = $this->getTrendData($currTotal, $prevTotal, true);

        // 5. Data untuk Line Chart Laporan Karhutla per periode
        $chartLabels = [];
        $chartCounts = [];

        if ($period === '30days') {
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $chartLabels[] = $date->locale('id')->isoFormat('D MMM');
                $chartCounts[$date->format('Y-m-d')] = 0;
            }
            $dbChartData = Report::select(DB::raw('DATE(created_at) as date_only'), DB::raw('count(*) as count'))
                ->where('created_at', '>=', Carbon::now()->subDays(29)->startOfDay())
                ->groupBy('date_only')->get();
            foreach ($dbChartData as $data) {
                if (isset($chartCounts[$data->date_only])) $chartCounts[$data->date_only] = $data->count;
            }
        } elseif ($period === 'month') {
            $daysInMonth = Carbon::now()->daysInMonth;
            $startOfMonth = Carbon::now()->startOfMonth();
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::now()->day($day);
                $chartLabels[] = $date->locale('id')->isoFormat('D MMM');
                $chartCounts[$date->format('Y-m-d')] = 0;
            }
            $dbChartData = Report::select(DB::raw('DATE(created_at) as date_only'), DB::raw('count(*) as count'))
                ->where('created_at', '>=', $startOfMonth)
                ->groupBy('date_only')->get();
            foreach ($dbChartData as $data) {
                if (isset($chartCounts[$data->date_only])) $chartCounts[$data->date_only] = $data->count;
            }
        } elseif ($period === 'year') {
            for ($m = 1; $m <= 12; $m++) {
                $date = Carbon::now()->month($m);
                $chartLabels[] = $date->locale('id')->isoFormat('MMMM');
                $chartCounts[$date->format('Y-m')] = 0;
            }
            
            $monthDateFormat = DB::getDriverName() === 'sqlite'
                ? "strftime('%Y-%m', created_at) as month_only"
                : "DATE_FORMAT(created_at, '%Y-%m') as month_only";

            $dbChartData = Report::select(DB::raw($monthDateFormat), DB::raw('count(*) as count'))
                ->where('created_at', '>=', $startOfYear)
                ->groupBy('month_only')
                ->get();

            foreach ($dbChartData as $data) {
                if (isset($chartCounts[$data->month_only])) $chartCounts[$data->month_only] = $data->count;
            }
        } else {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                if ($i === 0) $label = 'Hari Ini';
                elseif ($i === 1) $label = 'Kemarin';
                else $label = $date->locale('id')->isoFormat('D MMMM');
                $chartLabels[] = $label;
                $chartCounts[$date->format('Y-m-d')] = 0;
            }
            $dbChartData = Report::select(DB::raw('DATE(created_at) as date_only'), DB::raw('count(*) as count'))
                ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
                ->groupBy('date_only')->get();
            foreach ($dbChartData as $data) {
                if (isset($chartCounts[$data->date_only])) $chartCounts[$data->date_only] = $data->count;
            }
        }

        $chartDataValues = array_values($chartCounts);

        // 6. Insight Otomatis (Automated Insights)
        $insights = [];
        
        // A. Tingkat Penyelesaian Laporan (Resolution Rate)
        $totalValidDanProses = $laporanValid + $sedangDitangani + $laporanSelesai;
        $resolutionRate = $totalValidDanProses > 0 ? round(($laporanSelesai / $totalValidDanProses) * 100) : 0;
        if ($resolutionRate >= 75) {
            $insights[] = [
                'type' => 'success',
                'icon' => 'ph-check-circle',
                'title' => 'Tingkat Penyelesaian Tinggi (' . $resolutionRate . '%)',
                'desc' => 'Kinerja penanganan laporan karhutla sangat memuaskan. Sebagian besar laporan aktif telah berhasil diselesaikan oleh petugas lapangan.'
            ];
        } elseif ($resolutionRate >= 40) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'ph-warning-circle',
                'title' => 'Tingkat Penyelesaian Sedang (' . $resolutionRate . '%)',
                'desc' => 'Tingkat penyelesaian sedang. Harap terus dorong petugas pemadam lapangan untuk mempercepat pemadaman titik api.'
            ];
        } else {
            $insights[] = [
                'type' => 'danger',
                'icon' => 'ph-x-circle',
                'title' => 'Tingkat Penyelesaian Rendah (' . $resolutionRate . '%)',
                'desc' => 'Perhatian! Banyak laporan valid yang belum diselesaikan. Segera lakukan koordinasi intensif dengan pos pemadam terdekat.'
            ];
        }

        // B. Backlog Verifikasi (Pending backlog)
        if ($menungguVerifikasi > 5) {
            $insights[] = [
                'type' => 'danger',
                'icon' => 'ph-bell-ringing',
                'title' => 'Backlog Verifikasi Tinggi (' . $menungguVerifikasi . ' Laporan)',
                'desc' => 'Terdapat banyak laporan baru menunggu verifikasi. Harap segera periksa validitas laporan agar kebakaran bisa cepat dipadamkan.'
            ];
        } elseif ($menungguVerifikasi > 0) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'ph-clock',
                'title' => 'Menunggu Verifikasi (' . $menungguVerifikasi . ' Laporan)',
                'desc' => 'Terdapat laporan kebakaran baru masuk yang memerlukan verifikasi keabsahan data dari Administrator.'
            ];
        } else {
            $insights[] = [
                'type' => 'success',
                'icon' => 'ph-seal-check',
                'title' => 'Antrean Verifikasi Bersih',
                'desc' => 'Kerja bagus! Seluruh laporan masuk telah sukses diverifikasi oleh tim admin.'
            ];
        }

        // C. Hotspot Wilayah (Kalimantan Specific)
        $regions = ['Pontianak', 'Samarinda', 'Balikpapan', 'Palangka Raya', 'Banjarmasin', 'Tarakan', 'Tanjung Selor', 'Ketapang', 'Singkawang', 'Banjarbaru'];
        $regionCounts = [];
        foreach ($regions as $r) {
            $regionCounts[$r] = Report::where('address', 'like', "%{$r}%")->count();
        }
        arsort($regionCounts);
        $topRegion = key($regionCounts);
        $topRegionCount = current($regionCounts);
        if ($topRegionCount > 0) {
            $insights[] = [
                'type' => 'info',
                'icon' => 'ph-map-pin-line',
                'title' => 'Hotspot Utama: ' . $topRegion,
                'desc' => 'Wilayah ' . $topRegion . ' mencatat jumlah titik api terbanyak di Kalimantan dengan total ' . $topRegionCount . ' laporan.'
            ];
        }

        // D. Tingkat Keparahan Kebakaran Dominan (Fire Level)
        $dominantFireLevel = Report::select('fire_level', DB::raw('count(*) as count'))
            ->whereNotNull('fire_level')
            ->groupBy('fire_level')
            ->orderBy('count', 'desc')
            ->first();
        if ($dominantFireLevel) {
            $levelLabels = [
                'low' => 'Rendah (Low)',
                'medium' => 'Sedang (Medium)',
                'high' => 'Tinggi (High)',
                'critical' => 'Kritis (Critical)'
            ];
            $levelLabel = $levelLabels[$dominantFireLevel->fire_level] ?? ucfirst($dominantFireLevel->fire_level);
            
            if (in_array($dominantFireLevel->fire_level, ['high', 'critical'])) {
                $insights[] = [
                    'type' => 'danger',
                    'icon' => 'ph-fire-simple',
                    'title' => 'Tingkat Bahaya Dominan: ' . $levelLabel,
                    'desc' => 'Kebakaran berskala besar / kritis mendominasi wilayah laporan. Harap prioritaskan keselamatan warga dan penanganan darurat.'
                ];
            } else {
                $insights[] = [
                    'type' => 'info',
                    'icon' => 'ph-info',
                    'title' => 'Tingkat Bahaya Dominan: ' . $levelLabel,
                    'desc' => 'Mayoritas laporan terdeteksi berada pada tingkat bahaya sedang atau ringan.'
                ];
            }
        }

        return view('admin.dashboard', compact(
            'laporanMasuk',
            'totalLaporan',
            'laporanHariIni',
            'menungguVerifikasi',
            'laporanValid',
            'sedangDitangani',
            'laporanSelesai',
            'laporanDitolak',
            'trendHariIni',
            'trendMenungguVerifikasi',
            'trendSedangDitangani',
            'trendLaporanValid',
            'trendLaporanSelesai',
            'trendLaporanDitolak',
            'trendTotalLaporan',
            'chartLabels',
            'chartDataValues',
            'insights'
        ));
    }

    /**
     * Tampilkan halaman tren & distribusi laporan (PBI 36 & 37)
     */
    public function trenDistribusi(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $period = $request->input('period', '7days');
        $chartLabels = [];
        $dateKeys = [];

        if ($period === '30days') {
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $chartLabels[] = $date->locale('id')->isoFormat('D MMM');
                $dateKeys[] = $date->format('Y-m-d');
            }
            $groupRaw = 'DATE(created_at)';
            $rangeStart = Carbon::now()->subDays(29)->startOfDay();
        } elseif ($period === 'year') {
            for ($m = 1; $m <= 12; $m++) {
                $date = Carbon::now()->month($m);
                $chartLabels[] = $date->locale('id')->isoFormat('MMMM');
                $dateKeys[] = $date->format('Y-m');
            }
            $groupRaw = "DATE_FORMAT(created_at, '%Y-%m')";
            $rangeStart = Carbon::now()->startOfYear();
        } else {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $chartLabels[] = $i === 0 ? 'Hari Ini' : ($i === 1 ? 'Kemarin' : $date->locale('id')->isoFormat('D MMM'));
                $dateKeys[] = $date->format('Y-m-d');
            }
            $groupRaw = 'DATE(created_at)';
            $rangeStart = Carbon::now()->subDays(6)->startOfDay();
        }

        $statuses = ['pending', 'valid', 'diproses', 'selesai', 'ditolak'];
        $trenByStatus = [];

        foreach ($statuses as $status) {
            $counts = array_fill_keys($dateKeys, 0);
            $dbData = Report::select(DB::raw("$groupRaw as period_key"), DB::raw('count(*) as count'))
                ->where('status', $status)
                ->where('created_at', '>=', $rangeStart)
                ->groupBy('period_key')
                ->get();
            foreach ($dbData as $d) {
                if (isset($counts[$d->period_key])) {
                    $counts[$d->period_key] = $d->count;
                }
            }
            $trenByStatus[$status] = array_values($counts);
        }

        $statusLabels = ['Pending', 'Valid', 'Diproses', 'Selesai', 'Ditolak'];
        $statusData = [
            Report::where('status', 'pending')->count(),
            Report::where('status', 'valid')->count(),
            Report::where('status', 'diproses')->count(),
            Report::where('status', 'selesai')->count(),
            Report::where('status', 'ditolak')->count(),
        ];

        $wilayahRaw = Report::select('address', DB::raw('count(*) as count'))
            ->whereNotNull('address')
            ->where('address', '!=', '')
            ->groupBy('address')
            ->orderByDesc('count')
            ->limit(20)
            ->get();

        $wilayahMap = [];
        foreach ($wilayahRaw as $item) {
            $parts = array_map('trim', explode(',', $item->address));
            $wilayah = $parts[0] ?? 'Tidak diketahui';
            $wilayahMap[$wilayah] = ($wilayahMap[$wilayah] ?? 0) + $item->count;
        }
        arsort($wilayahMap);
        $wilayahMap = array_slice($wilayahMap, 0, 10);

        $wilayahLabels = array_keys($wilayahMap);
        $wilayahCounts = array_values($wilayahMap);

        return view('admin.tren-distribusi', compact(
            'chartLabels', 'trenByStatus', 'period',
            'statusLabels', 'statusData',
            'wilayahLabels', 'wilayahCounts'
        ));
    }

    public function show(Report $report)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        $report->load(['pelapor', 'penugasans.petugas']);
        $petugasTersedia = User::where('role', 'petugas')->get();
        return view('admin.reports.show', compact('report', 'petugasTersedia'));
    }

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

        $oldStatus = $report->status;
        $data = ['status' => $request->status];
        if ($request->status === 'ditolak') {
            $data['rejection_reason'] = $request->rejection_reason;
        }
        $report->update($data);

        // Kirim notifikasi ke pelapor (PBI 15)
        \App\Http\Controllers\NotifikasiController::createNotification(
            $report->user_id,
            'Status laporan Anda (#' . $report->report_id . ') telah diperbarui menjadi: ' . ucfirst($request->status) . '.'
        );

        Log::info('Admin report verified successfully', ['new_status' => $report->status]);

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

    public function assign(Report $report, User $petugas)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        if ($petugas->role !== 'petugas') {
            return redirect()->back()->withErrors(['error' => 'User yang ditunjuk bukan merupakan Petugas Lapangan.']);
        }

        $report->penugasans()->create(['petugas_id' => $petugas->users_id, 'assigned_at' => now()]);
        $oldStatus = $report->status;
        $report->update([
            'status' => 'diproses',
            'assigned_petugas_id' => $petugas->users_id,
        ]);

        // Kirim notifikasi ke pelapor (PBI 15)
        \App\Http\Controllers\NotifikasiController::createNotification(
            $report->user_id,
            'Laporan Anda (#' . $report->report_id . ') sedang diproses oleh petugas lapangan.'
        );

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

        // Kirim notifikasi ke pelapor (warga)
        if ($report->user_id) {
            \App\Http\Controllers\NotifikasiController::createNotification(
                $report->user_id,
                'Laporan "' . $report->title . '" Anda sedang ditangani oleh petugas.'
            );
        }

        // Kirim notifikasi ke petugas
        \App\Http\Controllers\NotifikasiController::createNotification(
            $petugas->users_id,
            'Anda ditugaskan untuk menangani laporan: "' . $report->title . '".'
        );

        return redirect()->back()->with('success', 'Petugas ' . $petugas->users_name . ' berhasil ditugaskan.');
    }

    /**
     * Ubah penugasan petugas ke petugas lain
     */
    public function reassign(Report $report, User $petugas)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Pastikan role user yang ditugaskan adalah petugas
        if ($petugas->role !== 'petugas') {
            return redirect()->back()->withErrors(['error' => 'User yang ditunjuk bukan merupakan Petugas Lapangan.']);
        }

        // Ambil petugas lama (dari assigned_petugas_id)
        $oldPetugasId = $report->assigned_petugas_id;
        $oldPetugas = User::find($oldPetugasId);

        // Jika petugas yang baru sama dengan petugas lama, tidak perlu diubah
        if ($oldPetugasId === $petugas->users_id) {
            return redirect()->back()->with('success', 'Petugas ini sudah ditugaskan pada laporan ini.');
        }

        // Mulai transaksi database untuk menjamin konsistensi
        DB::transaction(function () use ($report, $petugas, $oldPetugasId, $oldPetugas) {
            // Hapus penugasan aktif (yang belum selesai) untuk laporan ini
            Penugasan::where('report_id', $report->report_id)
                ->whereNull('completed_at')
                ->delete();

            // Buat penugasan baru untuk petugas baru
            $report->penugasans()->create([
                'petugas_id' => $petugas->users_id,
                'assigned_at' => now()
            ]);

            // Update assigned_petugas_id di tabel reports
            $report->update([
                'assigned_petugas_id' => $petugas->users_id,
            ]);

            $roleLabel = match (Auth::user()->role) {
                'masyarakat' => 'Pelapor',
                'petugas' => 'Admin Sistem',
                'admin' => 'Admin Sistem',
                default => ucfirst(Auth::user()->role)
            };

            $oldName = $oldPetugas ? $oldPetugas->users_name : 'Petugas Sebelumnya';

            // Tambahkan catatan ke riwayat status
            $report->statusHistories()->create([
                'status_awal' => 'diproses',
                'status_baru' => 'diproses',
                'catatan' => 'Penugasan petugas diubah dari ' . $oldName . ' menjadi ' . $petugas->users_name . '.',
                'diubah_oleh' => Auth::user()->users_name . ' (' . $roleLabel . ')',
                'tanggal_ubah' => now(),
            ]);
        });

        // Kirim notifikasi ke petugas lama bahwa penugasannya dibatalkan
        if ($oldPetugas) {
            \App\Http\Controllers\NotifikasiController::createNotification(
                $oldPetugas->users_id,
                'Penugasan Anda untuk laporan: "' . $report->title . '" telah dialihkan ke petugas lain.'
            );
        }

        // Kirim notifikasi ke petugas baru
        \App\Http\Controllers\NotifikasiController::createNotification(
            $petugas->users_id,
            'Anda ditugaskan untuk menangani laporan: "' . $report->title . '".'
        );

        // Kirim notifikasi ke pelapor (warga)
        if ($report->user_id) {
            \App\Http\Controllers\NotifikasiController::createNotification(
                $report->user_id,
                'Petugas penanganan laporan "' . $report->title . '" Anda telah diubah menjadi ' . $petugas->users_name . '.'
            );
        }

        return redirect()->back()->with('success', 'Penugasan berhasil diubah ke ' . $petugas->users_name . '.');
    }


    /**
     * Tampilkan daftar semua pengguna
     */
    public function usersIndex(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $activeRole = $request->input('role', 'petugas');
        if (!in_array($activeRole, ['petugas', 'masyarakat'])) {
            $activeRole = 'petugas';
        }

        $query = User::where('role', $activeRole)->latest('users_id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();
        return view('admin.users.index', compact('users', 'activeRole'));
    }

    public function usersEdit(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('admin.users.edit', compact('user'));
    }

    public function importPetugas(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $request->validate(['file' => 'required|mimes:csv,txt|max:2048']);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), "r");

        $header = true;
        $successCount = 0;
        $skippedCount = 0;
        $skippedDetails = [];
        $importedData = [];
        $rowNumber = 1;

        while (($row = fgetcsv($handle, 1000, ",")) !== false) {
            if ($header) { $header = false; $rowNumber++; continue; }
            if (count($row) >= 4) {
                $email = trim($row[1]);
                if (empty($email)) {
                    $skippedCount++;
                    $skippedDetails[] = "Baris $rowNumber: Email kosong.";
                } elseif (!User::where('email', $email)->exists()) {
                    try {
                        User::create([
                            'users_name' => trim($row[0]),
                            'email'      => $email,
                            'phone'      => trim($row[2]),
                            'password'   => bcrypt(trim($row[3])),
                            'role'       => 'petugas',
                        ]);
                        $successCount++;
                        $importedData[] = ['name' => trim($row[0]), 'email' => $email, 'phone' => trim($row[2])];
                    } catch (\Exception $e) {
                        $skippedCount++;
                        $skippedDetails[] = "Baris $rowNumber: Gagal menyimpan data ($email).";
                    }
                } else {
                    $skippedCount++;
                    $skippedDetails[] = "Baris $rowNumber: Email sudah terdaftar ($email).";
                }
            } else {
                if (!empty(array_filter($row))) {
                    $skippedCount++;
                    $skippedDetails[] = "Baris $rowNumber: Format kolom tidak lengkap.";
                }
            }
            $rowNumber++;
        }
        fclose($handle);

        return redirect()->route('admin.users.index', ['role' => 'petugas'])->with([
            'success' => $successCount . ' data petugas berhasil diimpor.',
            'import_summary' => ['skipped' => $skippedCount, 'details' => $skippedDetails, 'imported_data' => $importedData]
        ]);
    }

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

    public function destroy(Report $report)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        $report->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Laporan berhasil dihapus.');
    }

    /**
     * Tampilkan daftar seluruh laporan (semua status) untuk admin
     */
    public function reportsIndex(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = Report::with(['pelapor', 'penugasans.petugas'])->latest('report_id');

        // Filter Tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Wilayah
        if ($request->filled('region')) {
            $region = $request->region;
            $query->where('address', 'like', "%{$region}%");
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

        return view('admin.reports.index', compact('laporanMasuk'));
    }

    public function usersDestroy(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        if ($user->users_id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        $user->delete();
        return redirect()->back()->with('success', 'Data pengguna berhasil dihapus.');
    }

    /**
     * Hitung data kenaikan/penurunan (tren) dibanding periode sebelumnya
     */
    private function getTrendData($current, $previous, $isNegativeMetric = false)
    {
        $diff = $current - $previous;
        
        if ($previous == 0) {
            if ($current == 0) {
                return [
                    'percent' => 0,
                    'text' => 'Stabil (0)',
                    'class' => 'trend-neutral',
                    'icon' => 'ph-minus'
                ];
            }
            $class = $isNegativeMetric ? 'trend-up bad' : 'trend-up good';
            return [
                'percent' => 100,
                'text' => 'Naik +' . $current,
                'class' => $class,
                'icon' => 'ph-trend-up'
            ];
        }

        $percent = round(($diff / $previous) * 100);

        if ($diff > 0) {
            $class = $isNegativeMetric ? 'trend-up bad' : 'trend-up good';
            return [
                'percent' => $percent,
                'text' => 'Naik +' . $percent . '%',
                'class' => $class,
                'icon' => 'ph-trend-up'
            ];
        } elseif ($diff < 0) {
            $class = $isNegativeMetric ? 'trend-down good' : 'trend-down bad';
            return [
                'percent' => abs($percent),
                'text' => 'Turun ' . abs($percent) . '%',
                'class' => $class,
                'icon' => 'ph-trend-down'
            ];
        } else {
            return [
                'percent' => 0,
                'text' => 'Stabil (0%)',
                'class' => 'trend-neutral',
                'icon' => 'ph-minus'
            ];
        }
    }
}

