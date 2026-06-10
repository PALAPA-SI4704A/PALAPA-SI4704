<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>Daftar Laporan - Palapa</title>
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

        /* Urgency Badges */
        .badge-low { background: #e6f4ea; color: #137333; }
        .badge-medium { background: #fff3cd; color: #856404; }
        .badge-high { background: #fce8e6; color: #c5221f; }
        .badge-critical { background: #feebec; color: #c53030; font-weight: 800; border: 1px dashed #c53030; }

        .btn-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .btn-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        @media (max-width: 980px) {
            .layout { flex-direction: column; }
            .content { max-width: none !important; }
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
            <div style="background: #c6f6d5; color: #2f855a; padding: 14px 20px; border-radius: 12px; font-weight: 600; display: flex; align-items: center; gap: 10px; border: 1px solid #b2f5ea; margin-bottom: 12px;">
                <i class="ph ph-check-circle" style="font-size: 20px;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Table Laporan Masuk -->
        <div class="section">
            <h2 class="section-title">Daftar Seluruh Laporan</h2>
            
            <form class="filters" method="GET" action="{{ route('admin.reports.index') }}">
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

                <div class="filter-group">
                    <i class="ph ph-warning-circle"></i>
                    <select name="fire_level" class="filter-select" onchange="this.form.submit()">
                        <option value="">Urgensi</option>
                        <option value="low" {{ request('fire_level') == 'low' ? 'selected' : '' }}>Rendah</option>
                        <option value="medium" {{ request('fire_level') == 'medium' ? 'selected' : '' }}>Sedang</option>
                        <option value="high" {{ request('fire_level') == 'high' ? 'selected' : '' }}>Tinggi</option>
                        <option value="critical" {{ request('fire_level') == 'critical' ? 'selected' : '' }}>Kritis</option>
                    </select>
                </div>

                <div class="filter-group">
                    <i class="ph ph-map-pin"></i>
                    <select name="region" class="filter-select" onchange="this.form.submit()">
                        <option value="">Wilayah</option>
                        <option value="Pontianak" {{ request('region') == 'Pontianak' ? 'selected' : '' }}>Pontianak</option>
                        <option value="Samarinda" {{ request('region') == 'Samarinda' ? 'selected' : '' }}>Samarinda</option>
                        <option value="Balikpapan" {{ request('region') == 'Balikpapan' ? 'selected' : '' }}>Balikpapan</option>
                        <option value="Palangka Raya" {{ request('region') == 'Palangka Raya' ? 'selected' : '' }}>Palangka Raya</option>
                        <option value="Banjarmasin" {{ request('region') == 'Banjarmasin' ? 'selected' : '' }}>Banjarmasin</option>
                        <option value="Tarakan" {{ request('region') == 'Tarakan' ? 'selected' : '' }}>Tarakan</option>
                        <option value="Tanjung Selor" {{ request('region') == 'Tanjung Selor' ? 'selected' : '' }}>Tanjung Selor</option>
                        <option value="Ketapang" {{ request('region') == 'Ketapang' ? 'selected' : '' }}>Ketapang</option>
                        <option value="Singkawang" {{ request('region') == 'Singkawang' ? 'selected' : '' }}>Singkawang</option>
                        <option value="Banjarbaru" {{ request('region') == 'Banjarbaru' ? 'selected' : '' }}>Banjarbaru</option>
                    </select>
                </div>
                
                <div class="filter-group search-box">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" name="location" class="filter-input" placeholder="Cari Lokasi/Deskripsi..." value="{{ request('location') }}">
                </div>

                <button type="submit" class="filter-submit-btn">Cari</button>
            </form>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>DESKRIPSI</th>
                            <th>URGENSI</th>
                            <th>LOKASI</th>
                            <th>STATUS</th>
                            <th>TANGGAL PELAPORAN</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporanMasuk as $report)
                        <tr>
                            <td>{{ Str::limit($report->description, 50) }}</td>
                            <td>
                                @php
                                    $urgencyClass = 'badge-low';
                                    $urgencyText = 'Rendah';
                                    if($report->fire_level == 'medium') { $urgencyClass = 'badge-medium'; $urgencyText = 'Sedang'; }
                                    elseif($report->fire_level == 'high') { $urgencyClass = 'badge-high'; $urgencyText = 'Tinggi'; }
                                    elseif($report->fire_level == 'critical') { $urgencyClass = 'badge-critical'; $urgencyText = 'Kritis'; }
                                @endphp
                                <span class="badge {{ $urgencyClass }}">
                                    {{ $urgencyText }}
                                </span>
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
                                    <form action="{{ route('admin.reports.destroy', $report->report_id) }}" method="POST" @submit.prevent="$dispatch('open-confirm-modal', { message: 'Apakah Anda yakin ingin menghapus laporan ini?', form: $event.target })" style="margin: 0;">
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
</body>
</html>
