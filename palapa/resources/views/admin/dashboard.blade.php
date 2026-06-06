<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>Dashboard Admin - Palapa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons, AlpineJS & Chart.js -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --bg: #f3f5f8;
            --surface: #ffffff;
            --text: #2a2e38;
            --muted: #8a94a5;
            --line: #e5eaf1;
            --primary: #1f76c2;
            --primary-dark: #165f9e;
            --shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at 15% 10%, #f8fbff 0%, #f1f4f8 40%, #edf1f6 100%);
            color: var(--text);
            min-height: 100vh;
        }

        .layout {
            display: flex;
            gap: 24px;
            padding: 16px;
            min-height: 100vh;
        }

        .content {
            flex: 1;
            max-width: calc(100vw - 306px);
            transition: max-width 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        .stat-card {
            border-radius: 20px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.01);
            border: 1px solid rgba(229, 234, 241, 0.5);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.03);
        }

        .stat-card:nth-child(1) { background: #eef4f9; } /* Laporan Masuk Hari Ini */
        .stat-card:nth-child(2) { background: #eefaf3; } /* Laporan Menunggu Verifikasi */
        .stat-card:nth-child(3) { background: #fff5f5; } /* Laporan Sedang ditangani */
        .stat-card:nth-child(4) { background: #fffcf0; } /* Laporan Valid */
        .stat-card:nth-child(5) { background: #fbf0ff; } /* Laporan Selesai */
        .stat-card:nth-child(6) { background: #fff0f0; } /* Laporan Ditolak */
        .stat-card:nth-child(7) { background: #f0f7ff; } /* Total Laporan */

        .stat-card h3 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
            color: #4a5568;
        }

        .stat-card .value {
            font-size: 38px;
            font-weight: 800;
            color: #2d3748;
            margin: 0;
            line-height: 1;
        }

        .section {
            background: var(--surface);
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            border: 1px solid #e2e8f0;
        }

        .section-title {
            color: #0f66aa;
            font-size: 26px;
            font-weight: 800;
            margin: 0 0 20px 0;
        }

        .chart-container {
            position: relative;
            height: 320px;
            width: 100%;
        }

        .filters {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
            background: #f8fafc;
            padding: 16px;
            border-radius: 16px;
            align-items: center;
            border: 1px solid #edf2f7;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 13px;
        }

        .filter-group i {
            color: #a0aec0;
            font-size: 16px;
        }

        .filter-select, .filter-input {
            border: none;
            font-size: 13px;
            font-family: inherit;
            background: transparent;
            outline: none;
            color: #4a5568;
            width: 100%;
        }
        
        .filter-input {
            flex: 1;
        }

        .filter-group.search-box {
            flex: 1;
        }

        .filter-submit-btn {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .filter-submit-btn:hover {
            background: var(--primary-dark);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
        }

        th {
            padding: 14px 18px;
            color: #718096;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            border-bottom: 1px solid #edf2f7;
            background: #f8fafc;
        }

        td {
            padding: 16px 18px;
            border-bottom: 1px solid #edf2f7;
            color: #2d3748;
            vertical-align: middle;
        }

        tr:hover td {
            background-color: #fafbfc;
        }

        .table-img {
            width: 52px;
            height: 38px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #edf2f7;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
            text-align: center;
        }

        .badge-pending { background: #e6f0fd; color: #3182ce; }
        .badge-diproses { background: #fefcbf; color: #b7791f; }
        .badge-selesai { background: #c6f6d5; color: #2f855a; }
        .badge-valid { background: #e2fbf0; color: #2b6cb0; }
        .badge-ditolak { background: #fed7d7; color: #c53030; }

        .btn-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Trend & Header Styles for Cards */
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-header i {
            font-size: 22px;
            color: #718096;
            opacity: 0.8;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 8px;
            width: fit-content;
            margin-top: auto;
        }

        .trend-up.good { background: #e6f4ea; color: #137333; }
        .trend-up.bad { background: #fce8e6; color: #c5221f; }
        .trend-down.good { background: #e6f4ea; color: #137333; }
        .trend-down.bad { background: #fce8e6; color: #c5221f; }
        .trend-neutral { background: #f1f3f4; color: #5f6368; }

        /* Charts Grid Layout */
        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        /* Insights Section Styling */
        .insights-section {
            background: var(--surface);
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            border: 1px solid #e2e8f0;
        }
        
        .insights-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .insight-card {
            display: flex;
            gap: 16px;
            padding: 18px;
            border-radius: 16px;
            border: 1px solid transparent;
            transition: transform 0.2s;
        }

        .insight-card:hover {
            transform: translateY(-1px);
        }

        .insight-card i {
            font-size: 24px;
            margin-top: 2px;
        }

        .insight-card-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .insight-card-title {
            font-weight: 700;
            font-size: 14px;
        }

        .insight-card-desc {
            font-size: 12.5px;
            color: #4a5568;
            line-height: 1.5;
        }

        .insight-success { background: #f6fdf9; border-color: #c6f6d5; color: #1c7430; }
        .insight-success .insight-card-title { color: #1c7430; }
        .insight-success i { color: #28a745; }

        .insight-warning { background: #fffdf5; border-color: #fefcbf; color: #b7791f; }
        .insight-warning .insight-card-title { color: #b7791f; }
        .insight-warning i { color: #dd6b20; }

        .insight-danger { background: #fffcfc; border-color: #fed7d7; color: #c53030; }
        .insight-danger .insight-card-title { color: #c53030; }
        .insight-danger i { color: #e53e3e; }

        .insight-info { background: #f7fbfe; border-color: #bee3f8; color: #2b6cb0; }
        .insight-info .insight-card-title { color: #2b6cb0; }
        .insight-info i { color: #3182ce; }

        @media (max-width: 1200px) {
            .charts-grid { grid-template-columns: 1fr; }
            .insights-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 980px) {
            .layout { flex-direction: column; }
            .content { max-width: none !important; }
            .stats-grid { grid-template-columns: 1fr; gap: 16px; }
            .filters { flex-direction: column; align-items: stretch; gap: 12px; }
            .filter-group.search-box { flex: none; }
            .table-responsive { overflow-x: auto; }
        }
    </style>
</head>
<body>
<div class="layout" x-data="{ sidebarOpen: true }">
    @include('components.sidebar')

    <main class="content" :style="sidebarOpen ? 'max-width: calc(100vw - 306px);' : 'max-width: calc(100vw - 138px);'">
        
        <!-- Flash Message -->
        @if(session('success'))
            <div style="background: #c6f6d5; color: #2f855a; padding: 14px 20px; border-radius: 12px; font-weight: 600; display: flex; align-items: center; gap: 10px; border: 1px solid #b2f5ea;">
                <i class="ph ph-check-circle" style="font-size: 22px;"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Cards Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <h3>Laporan Masuk Hari Ini</h3>
                    <i class="ph ph-calendar-plus"></i>
                </div>
                <p class="value">{{ $laporanHariIni }}</p>
                <div class="stat-trend {{ $trendHariIni['class'] }}">
                    <i class="ph {{ $trendHariIni['icon'] }}"></i>
                    <span>{{ $trendHariIni['text'] }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <h3>Laporan Menunggu Verifikasi</h3>
                    <i class="ph ph-clock-counter-clockwise"></i>
                </div>
                <p class="value">{{ $menungguVerifikasi }}</p>
                <div class="stat-trend {{ $trendMenungguVerifikasi['class'] }}">
                    <i class="ph {{ $trendMenungguVerifikasi['icon'] }}"></i>
                    <span>{{ $trendMenungguVerifikasi['text'] }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <h3>Laporan Sedang Ditangani</h3>
                    <i class="ph ph-shield-warning"></i>
                </div>
                <p class="value">{{ $sedangDitangani }}</p>
                <div class="stat-trend {{ $trendSedangDitangani['class'] }}">
                    <i class="ph {{ $trendSedangDitangani['icon'] }}"></i>
                    <span>{{ $trendSedangDitangani['text'] }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <h3>Laporan Valid</h3>
                    <i class="ph ph-check-square-offset"></i>
                </div>
                <p class="value">{{ $laporanValid }}</p>
                <div class="stat-trend {{ $trendLaporanValid['class'] }}">
                    <i class="ph {{ $trendLaporanValid['icon'] }}"></i>
                    <span>{{ $trendLaporanValid['text'] }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <h3>Laporan Selesai</h3>
                    <i class="ph ph-sparkle"></i>
                </div>
                <p class="value">{{ $laporanSelesai }}</p>
                <div class="stat-trend {{ $trendLaporanSelesai['class'] }}">
                    <i class="ph {{ $trendLaporanSelesai['icon'] }}"></i>
                    <span>{{ $trendLaporanSelesai['text'] }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <h3>Laporan Ditolak</h3>
                    <i class="ph ph-prohibit"></i>
                </div>
                <p class="value">{{ $laporanDitolak }}</p>
                <div class="stat-trend {{ $trendLaporanDitolak['class'] }}">
                    <i class="ph {{ $trendLaporanDitolak['icon'] }}"></i>
                    <span>{{ $trendLaporanDitolak['text'] }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <h3>Total Laporan</h3>
                    <i class="ph ph-files"></i>
                </div>
                <p class="value">{{ $totalLaporan }}</p>
                <div class="stat-trend {{ $trendTotalLaporan['class'] }}">
                    <i class="ph {{ $trendTotalLaporan['icon'] }}"></i>
                    <span>{{ $trendTotalLaporan['text'] }}</span>
                </div>
            </div>
        </div>

        <!-- Charts Grid Section -->
        <div class="charts-grid">
            <!-- Line Chart Section -->
            <div class="section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                    <h2 class="section-title" style="margin: 0;">Laporan Karhutla Per Periode</h2>
                    <form id="chartFilterForm" method="GET" action="{{ route('admin.dashboard') }}" style="margin: 0;">
                        @if(request('date')) <input type="hidden" name="date" value="{{ request('date') }}"> @endif
                        @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                        @if(request('location')) <input type="hidden" name="location" value="{{ request('location') }}"> @endif
                        
                        <div class="filter-group" style="padding: 4px 8px; margin: 0; display: inline-flex; align-items: center; gap: 8px; background: white; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 13px;">
                            <i class="ph ph-calendar-blank" style="color: #a0aec0; font-size: 16px;"></i>
                            <select name="period" class="filter-select" onchange="this.form.submit()" style="border: none; font-size: 13px; font-family: inherit; background: transparent; outline: none; color: #4a5568;">
                                <option value="7days" {{ request('period') == '7days' || !request('period') ? 'selected' : '' }}>7 Hari Terakhir</option>
                                <option value="30days" {{ request('period') == '30days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                                <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                                <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="chart-container">
                    <canvas id="karhutlaChart"></canvas>
                </div>
            </div>

            <!-- Pie Chart Section -->
            <div class="section" style="display: flex; flex-direction: column;">
                <h2 class="section-title" style="margin-bottom: 20px;">Distribusi Status Laporan</h2>
                <div class="chart-container" style="flex: 1; display: flex; align-items: center; justify-content: center; min-height: 250px;">
                    <canvas id="statusPieChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Automated Insights Section -->
        <div class="insights-section">
            <h2 class="section-title" style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                <i class="ph ph-lightbulb-filament" style="color: #ecc94b; font-size: 28px;"></i>
                Insight Kondisi Utama
            </h2>
            <div class="insights-grid">
                @forelse($insights as $insight)
                    <div class="insight-card insight-{{ $insight['type'] }}">
                        <i class="ph {{ $insight['icon'] }}"></i>
                        <div class="insight-card-content">
                            <span class="insight-card-title">{{ $insight['title'] }}</span>
                            <span class="insight-card-desc">{{ $insight['desc'] }}</span>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: span 2; text-align: center; color: #a0aec0; padding: 12px;">
                        Tidak ada data yang cukup untuk memuat insight otomatis saat ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Table Laporan Masuk -->
        <div class="section">
            <h2 class="section-title">Laporan Masuk</h2>
            
            <form class="filters" method="GET" action="{{ route('admin.dashboard') }}">
                @if(request('period'))
                    <input type="hidden" name="period" value="{{ request('period') }}">
                @endif
                <div class="filter-group">
                    <i class="ph ph-calendar"></i>
                    <input type="date" name="date" class="filter-select" value="{{ request('date') }}" onchange="this.form.submit()">
                </div>
                
                <div class="filter-group">
                    <i class="ph ph-funnel"></i>
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                        <option value="valid" {{ request('status') == 'valid' ? 'selected' : '' }}>Valid</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                
                <div class="filter-group search-box">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" name="location" class="filter-input" placeholder="Cari Lokasi..." value="{{ request('location') }}">
                </div>

                <button type="submit" class="filter-submit-btn">Cari</button>
            </form>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>FOTO</th>
                            <th>LOKASI</th>
                            <th>STATUS</th>
                            <th>TANGGAL PELAPORAN</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporanMasuk as $report)
                        <tr>
                            <td>#{{ $report->report_id }}</td>
                            <td>
                                @if($report->photo)
                                    <img src="{{ route('reports.photo', ['path' => $report->photo]) }}" class="table-img" alt="Foto Laporan">
                                @else
                                    <div class="table-img" style="background:#e2e8f0; display:flex; align-items:center; justify-content:center; color:#a0aec0;">
                                        <i class="ph ph-image-square" style="font-size: 20px;"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($report->address)
                                    {{ Str::limit($report->address, 50) }}
                                @else
                                    {{ $report->latitude }}, {{ $report->longitude }}
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = 'badge-pending';
                                    if($report->status == 'diproses') $statusClass = 'badge-diproses';
                                    if($report->status == 'selesai') $statusClass = 'badge-selesai';
                                    if($report->status == 'valid') $statusClass = 'badge-valid';
                                    if($report->status == 'ditolak') $statusClass = 'badge-ditolak';
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    @if($report->status == 'pending') Menunggu Verifikasi @else {{ ucfirst($report->status) }} @endif
                                </span>
                                @if(in_array($report->status, ['diproses', 'selesai']) && $report->penugasans->isNotEmpty())
                                    <div style="font-size: 11px; color: #4a5568; margin-top: 6px; display: flex; flex-direction: column; gap: 2px;">
                                        <span style="font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="ph ph-user-helmet" style="font-size: 12px; color: #718096;"></i> Petugas:
                                        </span>
                                        @foreach($report->penugasans as $p)
                                            <span style="color: #2d3748; padding-left: 2px;">• {{ $p->petugas?->users_name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>{{ $report->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <a href="{{ route('admin.reports.show', $report) }}" class="btn-link">[Lihat]</a>
                                    <form action="{{ route('admin.reports.destroy', $report->report_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan tidak valid ini?');" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #e53e3e; cursor: pointer; font-size: 14px; padding: 0;">[Hapus]</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #a0aec0; padding: 24px;">Belum ada laporan masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Line Chart
        const ctx = document.getElementById('karhutlaChart').getContext('2d');
        
        // Data dari Controller
        const labels = {!! json_encode($chartLabels) !!};
        const dataValues = {!! json_encode($chartDataValues) !!};

        // Buat gradient background di bawah garis
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(159, 122, 234, 0.3)');
        gradient.addColorStop(1, 'rgba(159, 122, 234, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: dataValues,
                    borderColor: '#9f7aea',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4, // Membuat kurva melengkung mulus
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#000000',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#2d3748',
                        titleFont: { family: 'Poppins', size: 13 },
                        bodyFont: { family: 'Poppins', size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#edf2f7'
                        },
                        ticks: {
                            font: { family: 'Poppins', size: 11 },
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { family: 'Poppins', size: 11 }
                        }
                    }
                }
            }
        });

        // Status Pie Chart
        const pieCtx = document.getElementById('statusPieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Valid', 'Diproses', 'Selesai', 'Ditolak'],
                datasets: [{
                    data: [
                        {{ $menungguVerifikasi }},
                        {{ $laporanValid }},
                        {{ $sedangDitangani }},
                        {{ $laporanSelesai }},
                        {{ $laporanDitolak }}
                    ],
                    backgroundColor: [
                        '#3182ce', // Pending - blue
                        '#2b6cb0', // Valid - darker blue
                        '#dd6b20', // Diproses - orange
                        '#2f855a', // Selesai - green
                        '#e53e3e'  // Ditolak - red
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: 'Poppins', size: 11 },
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: '#2d3748',
                        titleFont: { family: 'Poppins', size: 12 },
                        bodyFont: { family: 'Poppins', size: 12 },
                        padding: 10,
                        cornerRadius: 8
                    }
                }
            }
        });
    });
</script>
</body>
</html>
