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

        .btn-link {
            color: #3182ce;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            transition: color 0.2s;
        }
        
        .btn-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
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
                <h3>Laporan Masuk Hari Ini</h3>
                <p class="value">{{ $laporanHariIni }}</p>
            </div>
            <div class="stat-card">
                <h3>Laporan Menunggu Verifikasi</h3>
                <p class="value">{{ $menungguVerifikasi }}</p>
            </div>
            <div class="stat-card">
                <h3>Laporan Sedang Ditangani</h3>
                <p class="value">{{ $sedangDitangani }}</p>
            </div>
            <div class="stat-card">
                <h3>Laporan Valid</h3>
                <p class="value">{{ $laporanValid }}</p>
            </div>
            <div class="stat-card">
                <h3>Laporan Selesai</h3>
                <p class="value">{{ $laporanSelesai }}</p>
            </div>
            <div class="stat-card">
                <h3>Laporan Ditolak</h3>
                <p class="value">{{ $laporanDitolak }}</p>
            </div>
            <div class="stat-card">
                <h3>Total Laporan</h3>
                <p class="value">{{ $totalLaporan }}</p>
            </div>
        </div>

        <!-- Chart Section -->
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
                            <td><a href="{{ route('admin.reports.show', $report) }}" class="btn-link">[Lihat]</a></td>
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
    });
</script>
</body>
</html>
