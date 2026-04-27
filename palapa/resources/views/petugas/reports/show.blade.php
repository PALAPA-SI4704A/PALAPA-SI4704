<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - Palapa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons & AlpineJS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- LeafletJS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        :root {
            --bg: #f3f5f8;
            --surface: #ffffff;
            --text: #2a2e38;
            --muted: #8a94a5;
            --primary: #1f76c2;
            --primary-dark: #165f9e;
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

        .main-panel {
            background: var(--surface);
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            border: 1px solid #e2e8f0;
        }

        .report-header {
            margin-bottom: 24px;
        }

        .report-title {
            color: #0f66aa;
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 4px 0;
        }

        .report-subtitle {
            color: #718096;
            font-size: 14px;
            margin: 0;
        }

        .report-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }

        .detail-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 16px;
            min-height: 80px;
        }
        
        .detail-box.full-width {
            grid-column: 1 / -1;
            min-height: 120px;
        }

        .detail-label {
            font-size: 12px;
            color: #718096;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: block;
        }

        .detail-value {
            font-size: 14px;
            color: #2d3748;
            font-weight: 500;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-diproses { background: #fefcbf; color: #b7791f; }
        .badge-baru { background: #e6f0fd; color: #3182ce; }
        .badge-selesai { background: #c6f6d5; color: #2f855a; }

        .section-title {
            color: #0f66aa;
            font-size: 24px;
            font-weight: 800;
            margin: 0 0 16px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
            background: var(--surface);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        th {
            padding: 12px 16px;
            color: #718096;
            font-weight: 700;
            border-bottom: 1px solid #edf2f7;
            background: #f8fafc;
        }

        td {
            padding: 16px;
            border-bottom: 1px solid #edf2f7;
            color: #2d3748;
            vertical-align: middle;
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

        .badge-available { background: #c6f6d5; color: #2f855a; }
        .badge-onduty { background: #fefcbf; color: #b7791f; }

        @media (max-width: 980px) {
            .layout { flex-direction: column; }
            .content { max-width: none !important; }
            .report-details-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="layout" x-data="{ sidebarOpen: true }">
    @include('components.sidebar-petugas')

    <main class="content" :style="sidebarOpen ? 'max-width: calc(100vw - 306px);' : 'max-width: calc(100vw - 138px);'">
        
        <div class="main-panel">
            <div class="report-header">
                <h1 class="report-title">{{ $report->title ?? 'Laporan Titik Api' }}</h1>
                <p class="report-subtitle">#{{ $report->report_id }} Detail Laporan</p>
            </div>

            <div class="report-details-grid">
                <div class="detail-box">
                    <span class="detail-label">STATUS</span>
                    @php
                        $statusClass = 'badge-baru';
                        if($report->status == 'diproses') $statusClass = 'badge-diproses';
                        if($report->status == 'selesai') $statusClass = 'badge-selesai';
                    @endphp
                    <span class="badge {{ $statusClass }}">
                        @if($report->status == 'diproses') 🕒 @endif
                        {{ ucfirst($report->status) }}
                    </span>
                </div>
                
                <div class="detail-box">
                    <span class="detail-label">TANGGAL PELAPORAN</span>
                    <span class="detail-value">{{ $report->created_at->format('d F Y, H:i') }}</span>
                </div>
                
                <div class="detail-box">
                    <span class="detail-label">LOKASI (KOORDINAT)</span>
                    <span class="detail-value">{{ $report->latitude }}, {{ $report->longitude }}</span>
                </div>

                <div class="detail-box">
                    <span class="detail-label">PELAPOR</span>
                    <span class="detail-value">{{ $report->pelapor->users_name ?? 'Anonim' }}</span>
                </div>

                <div class="detail-box full-width">
                    <span class="detail-label">PETA LOKASI</span>
                    <div id="map-preview" style="height: 300px; border-radius: 8px; border: 1px solid #e2e8f0; z-index: 1;"></div>
                </div>

                <div class="detail-box full-width">
                    <span class="detail-label">DESKRIPSI KEJADIAN</span>
                    <span class="detail-value">{{ $report->description }}</span>
                </div>

                @if($report->photo)
                <div class="detail-box full-width">
                    <span class="detail-label">FOTO KEJADIAN</span>
                    <img src="{{ route('reports.photo', ['path' => $report->photo]) }}" alt="Foto Kejadian" style="max-width: 100%; max-height: 300px; border-radius: 8px;">
                </div>
                @endif
            </div>
        </div>

        <div>
            <h2 class="section-title">Petugas Tersedia</h2>
            
            @if(session('success'))
                <div style="background: #c6f6d5; color: #2f855a; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                    {{ session('success') }}
                </div>
            @endif

            <div style="overflow-x: auto;">
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
                                @php
                                    $isAssigned = \App\Models\Penugasan::where('petugas_id', $petugas->users_id)
                                                    ->whereNull('completed_at')
                                                    ->exists();
                                @endphp
                                @if($isAssigned)
                                    <span class="badge badge-onduty">On Duty</span>
                                @else
                                    <span class="badge badge-available">Available</span>
                                @endif
                            </td>
                            <td>~ km</td>
                            <td>
                                <form action="{{ route('petugas.reports.assign', ['report' => $report, 'petugas' => $petugas]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-action">[Tugaskan Petugas]</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #a0aec0;">Tidak ada petugas tersedia.</td>
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
        let lat = {{ $report->latitude }};
        let lng = {{ $report->longitude }};
        
        const map = L.map('map-preview').setView([lat, lng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup("<b>Lokasi Kejadian</b><br>{{ $report->latitude }}, {{ $report->longitude }}")
            .openPopup();
    });
</script>
</body>
</html>