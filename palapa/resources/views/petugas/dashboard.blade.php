<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama Petugas - Palapa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons & AlpineJS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
            gap: 16px;
        }

        .stat-card {
            border-radius: 16px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .stat-card:nth-child(1) { background: #eef4f9; }
        .stat-card:nth-child(2) { background: #fffcf0; }
        .stat-card:nth-child(3) { background: #eefaf3; }
        .stat-card:nth-child(4) { background: #eefaf3; }

        .stat-card h3 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: #4a5568;
        }

        .stat-card .value {
            font-size: 32px;
            font-weight: 800;
            color: #2d3748;
            margin: 0;
        }

        .section {
            background: var(--surface);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }

        .section-header {
            margin-bottom: 16px;
        }

        .section-title {
            color: #0f66aa;
            font-size: 24px;
            font-weight: 800;
            margin: 0 0 16px 0;
        }

        .filters {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
            background: #f8fafc;
            padding: 12px;
            border-radius: 12px;
        }

        .filter-select, .filter-input {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 13px;
            font-family: inherit;
            background: white;
            outline: none;
            color: #4a5568;
        }
        
        .filter-input {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
        }

        th {
            padding: 12px 16px;
            color: #718096;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            border-bottom: 1px solid #edf2f7;
        }

        td {
            padding: 16px;
            border-bottom: 1px solid #edf2f7;
            color: #2d3748;
            vertical-align: middle;
        }

        .table-img {
            width: 48px;
            height: 36px;
            border-radius: 6px;
            object-fit: cover;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-baru { background: #e6f0fd; color: #3182ce; }
        .badge-diproses { background: #fefcbf; color: #b7791f; }
        .badge-selesai { background: #c6f6d5; color: #2f855a; }
        .badge-onduty { background: #fefcbf; color: #b7791f; }
        .badge-available { background: #c6f6d5; color: #2f855a; }

        .btn-link {
            color: #3182ce;
            text-decoration: none;
            font-weight: 600;
        }
        .btn-link:hover {
            text-decoration: underline;
        }

        .btn-action {
            background: none;
            border: none;
            color: #4a5568;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
            padding: 0;
            font-family: inherit;
        }
        
        .btn-action:hover {
            color: #2d3748;
            text-decoration: underline;
        }

        .petugas-info {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
        }

        .petugas-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 980px) {
            .layout { flex-direction: column; }
            .content { max-width: none !important; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .filters { flex-direction: column; }
            .table-responsive { overflow-x: auto; }
        }
    </style>
</head>
<body>
<div class="layout" x-data="{ sidebarOpen: true }">
    @include('components.sidebar-petugas')

    <main class="content" :style="sidebarOpen ? 'max-width: calc(100vw - 306px);' : 'max-width: calc(100vw - 138px);'">
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Laporan Masuk Hari Ini</h3>
                <p class="value">{{ $laporanHariIni }}</p>
            </div>
            <div class="stat-card">
                <h3>Diproses</h3>
                <p class="value">{{ $diproses }}</p>
            </div>
            <div class="stat-card">
                <h3>Selesai Di tangani</h3>
                <p class="value">{{ $selesai }}</p>
            </div>
            <div class="stat-card">
                <h3>Total Laporan</h3>
                <p class="value">{{ $total }}</p>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Laporan Masuk</h2>
            <div class="filters">
                <select class="filter-select">
                    <option>Date range</option>
                </select>
                <select class="filter-select">
                    <option>Status</option>
                </select>
                <input type="text" class="filter-input" placeholder="Cari Lokasi">
            </div>

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
                                    <img src="{{ route('reports.photo', ['path' => $report->photo]) }}" class="table-img" alt="Foto">
                                @else
                                    <div class="table-img" style="background:#e2e8f0;"></div>
                                @endif
                            </td>
                            <td>{{ $report->latitude }}, {{ $report->longitude }}</td>
                            <td>
                                @php
                                    $statusClass = 'badge-baru';
                                    if($report->status == 'diproses') $statusClass = 'badge-diproses';
                                    if($report->status == 'selesai') $statusClass = 'badge-selesai';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($report->status) }}</span>
                            </td>
                            <td>{{ $report->created_at->format('d/m/Y') }}</td>
                            <td><a href="{{ route('petugas.reports.show', $report) }}" class="btn-link">[Lihat]</a></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #a0aec0;">Belum ada laporan masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Petugas Tersedia</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Jarak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($petugasTersedia as $petugas)
                        <tr>
                            <td>
                                <div class="petugas-info">
                                    <div class="petugas-avatar">👩‍🚒</div>
                                    {{ $petugas->users_name }}
                                </div>
                            </td>
                            <td>
                                <!-- Placeholder status for petugas -->
                                <span class="badge badge-available">Available</span>
                            </td>
                            <td>~ km</td>
                            <td>
                                <button class="btn-action">[Tugaskan Petugas]</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #a0aec0;">Tidak ada petugas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>
</body>
</html>