<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
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
        .badge-ditolak { background: #fed7d7; color: #c53030; }

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
    @include('components.sidebar')

    <main class="content" :style="sidebarOpen ? 'max-width: calc(100vw - 306px);' : 'max-width: calc(100vw - 138px);'">
        
        <div class="main-panel">
            @if(session('success'))
                <div style="background: #c6f6d5; color: #2f855a; padding: 12px; border-radius: 8px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <i class="ph ph-check-circle" style="font-size: 20px;"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background: #fed7d7; color: #c53030; padding: 12px; border-radius: 8px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <i class="ph ph-warning-circle" style="font-size: 20px;"></i>
                    Terjadi kesalahan: {{ $errors->first() }}
                </div>
            @endif

            <div class="report-header">
                <h1 class="report-title">{{ $report->title ?? 'Laporan Titik Api' }}</h1>
                <p class="report-subtitle">#{{ $report->report_id }} Detail Laporan</p>
            </div>

            <div class="report-details-grid">
                <div class="detail-box">
                    <span class="detail-label">STATUS</span>
                    @php
                        $statusColors = [
                            'ditolak' => 'background: #fed7d7; color: #c53030; border: 1px solid #feb2b2;', // Invalid - Merah
                            'pending' => 'background: #e2e8f0; color: #4a5568; border: 1px solid #cbd5e0;', // Pending - Abu-abu
                            'valid' => 'background: #c6f6d5; color: #2f855a; border: 1px solid #9ae6b4;',   // Verified - Hijau
                            'diproses' => 'background: #fefcbf; color: #b7791f; border: 1px solid #fbd38d;', // In Progress - Kuning
                            'selesai' => 'background: #ebf8ff; color: #2b6cb0; border: 1px solid #bee3f8;',  // Resolved - Biru
                        ];
                        $statusLabels = [
                            'ditolak' => 'Invalid',
                            'pending' => 'Pending',
                            'valid' => 'Verified',
                            'diproses' => 'In Progress',
                            'selesai' => 'Resolved',
                        ];
                    @endphp
                    <span class="badge" style="{{ $statusColors[$report->status] ?? 'background: #e2e8f0; color: #4a5568;' }}">
                        {{ $statusLabels[$report->status] ?? ucfirst($report->status) }}
                    </span>
                </div>

                <div class="detail-box">
                    <span class="detail-label">LEVEL KEBAKARAN</span>
                    @if($report->fire_level)
                        @php
                            $levelColors = [
                                'low' => 'background: #edf2f7; color: #4a5568; border: 1px solid #cbd5e0;',
                                'medium' => 'background: #fffaf0; color: #dd6b20; border: 1px solid #fbd38d;',
                                'high' => 'background: #fff5f5; color: #c53030; border: 1px solid #feb2b2;',
                                'critical' => 'background: #ffebeb; color: #9b2c2c; border: 1px solid #feb2b2;'
                            ];
                            $levelLabels = [
                                'low' => 'Low',
                                'medium' => 'Medium',
                                'high' => 'High',
                                'critical' => 'Critical'
                            ];
                        @endphp
                        <span class="badge" style="{{ $levelColors[$report->fire_level] ?? '' }}">
                            {{ $levelLabels[$report->fire_level] ?? ucfirst($report->fire_level) }}
                        </span>
                    @else
                        <span style="font-style: italic; color: #a0aec0; font-size: 13px;">Tidak ditentukan</span>
                    @endif
                </div>
                
                <div class="detail-box">
                    <span class="detail-label">TANGGAL PELAPORAN</span>
                    <span class="detail-value">{{ $report->created_at->format('d F Y, H:i') }}</span>
                </div>
                
                <div class="detail-box">
                    <span class="detail-label">LOKASI</span>
                    <span class="detail-value" style="display: flex; flex-direction: column; gap: 8px;">
                        <span>{{ $report->latitude }}, {{ $report->longitude }}</span>
                        @if($report->address)
                            <span style="font-size: 13px; color: #4a5568; display: flex; align-items: flex-start; gap: 4px;">
                                <i class="ph ph-map-pin" style="margin-top: 2px;"></i> 
                                {{ $report->address }}
                            </span>
                        @else
                            <span style="font-size: 13px; color: #a0aec0; font-style: italic; display: flex; align-items: flex-start; gap: 4px;">
                                <i class="ph ph-map-pin" style="margin-top: 2px;"></i> 
                                Area tidak diketahui
                            </span>
                        @endif
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $report->latitude }},{{ $report->longitude }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; background: #e6f0fd; color: #3182ce; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; width: fit-content; margin-top: 4px; transition: background 0.2s;">
                            <i class="ph ph-arrow-square-out"></i> Buka di Google Maps
                        </a>
                    </span>
                </div>

                <div class="detail-box">
                    <span class="detail-label">PELAPOR</span>
                    <span class="detail-value">{{ $report->pelapor?->users_name ?? 'Anonim' }}</span>
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

                @if($report->status === 'ditolak' && $report->rejection_reason)
                <div class="detail-box full-width" style="background: #fff5f5; border: 1px solid #fed7d7;">
                    <span class="detail-label" style="color: #c53030;">ALASAN PENOLAKAN</span>
                    <span class="detail-value" style="color: #c53030;">{{ $report->rejection_reason }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Form Update Status Penanganan Laporan (Khusus Petugas) -->
        <div class="main-panel" style="margin-bottom: 24px;">
            <h2 class="section-title">Update Status Penanganan</h2>
            <p style="margin-bottom: 16px; color: #718096; font-size: 14px;">Petugas pemadam dapat memperbarui status penanganan laporan secara langsung.</p>
            
            <form action="{{ route('petugas.reports.updateStatus', $report->report_id) }}" method="POST" style="margin: 0; display: flex; flex-direction: column; gap: 16px;">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 12px;">
                    <label style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 12px; cursor: pointer; display: flex; flex-direction: column; gap: 6px; transition: all 0.2s;" onmouseover="this.style.borderColor='#cbd5e0'" onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='#e2e8f0'" class="status-option">
                        <input type="radio" name="status" value="pending" {{ $report->status === 'pending' ? 'checked' : '' }} style="margin: 0; accent-color: #4a5568;" required>
                        <span style="font-weight: 700; font-size: 13px; color: #4a5568;">Pending</span>
                        <span style="font-size: 11px; color: #718096;">Belum direview</span>
                    </label>

                    <label style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 12px; cursor: pointer; display: flex; flex-direction: column; gap: 6px; transition: all 0.2s;" onmouseover="this.style.borderColor='#cbd5e0'" onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='#e2e8f0'" class="status-option">
                        <input type="radio" name="status" value="valid" {{ $report->status === 'valid' ? 'checked' : '' }} style="margin: 0; accent-color: #2f855a;" required>
                        <span style="font-weight: 700; font-size: 13px; color: #2f855a;">Verified</span>
                        <span style="font-size: 11px; color: #718096;">Laporan Valid</span>
                    </label>

                    <label style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 12px; cursor: pointer; display: flex; flex-direction: column; gap: 6px; transition: all 0.2s;" onmouseover="this.style.borderColor='#cbd5e0'" onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='#e2e8f0'" class="status-option">
                        <input type="radio" name="status" value="diproses" {{ $report->status === 'diproses' ? 'checked' : '' }} style="margin: 0; accent-color: #b7791f;" required>
                        <span style="font-weight: 700; font-size: 13px; color: #b7791f;">In Progress</span>
                        <span style="font-size: 11px; color: #718096;">Sedang ditangani</span>
                    </label>

                    <label style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 12px; cursor: pointer; display: flex; flex-direction: column; gap: 6px; transition: all 0.2s;" onmouseover="this.style.borderColor='#cbd5e0'" onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='#e2e8f0'" class="status-option">
                        <input type="radio" name="status" value="selesai" {{ $report->status === 'selesai' ? 'checked' : '' }} style="margin: 0; accent-color: #2b6cb0;" required>
                        <span style="font-weight: 700; font-size: 13px; color: #2b6cb0;">Resolved</span>
                        <span style="font-size: 11px; color: #718096;">Sudah selesai</span>
                    </label>

                    <label style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 12px; cursor: pointer; display: flex; flex-direction: column; gap: 6px; transition: all 0.2s;" onmouseover="this.style.borderColor='#cbd5e0'" onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='#e2e8f0'" class="status-option">
                        <input type="radio" name="status" value="ditolak" {{ $report->status === 'ditolak' ? 'checked' : '' }} style="margin: 0; accent-color: #c53030;" required>
                        <span style="font-weight: 700; font-size: 13px; color: #c53030;">Invalid</span>
                        <span style="font-size: 11px; color: #718096;">Tidak valid / hoax</span>
                    </label>
                </div>

                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 13px; font-weight: 700; color: #4a5568;">Catatan Tindakan / Deskripsi Penanganan (Opsional)</label>
                    <textarea name="catatan" rows="3" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; font-family: inherit; font-size: 13px; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#1f76c2'" onblur="this.style.borderColor='#e2e8f0'" placeholder="Masukkan catatan penanganan lapangan, misalnya: armada pemadam telah dikerahkan ke lokasi..."></textarea>
                </div>

                <button type="submit" style="background: var(--primary); color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 6px rgba(31, 118, 194, 0.15); transition: background 0.2s; width: fit-content;" onmouseover="this.style.background='var(--primary-dark)'" onmouseout="this.style.background='var(--primary)'">
                    <i class="ph ph-floppy-disk" style="font-size: 18px;"></i> Simpan Perubahan Status
                </button>
            </form>
        </div>

        <script>
            // Highlight selected status option card on load and on change
            document.addEventListener('DOMContentLoaded', () => {
                const radios = document.querySelectorAll('input[name="status"]');
                const updateBorders = () => {
                    radios.forEach(radio => {
                        const card = radio.closest('.status-option');
                        if (radio.checked) {
                            const val = radio.value;
                            if (val === 'pending') card.style.borderColor = '#4a5568';
                            if (val === 'valid') card.style.borderColor = '#2f855a';
                            if (val === 'diproses') card.style.borderColor = '#b7791f';
                            if (val === 'selesai') card.style.borderColor = '#2b6cb0';
                            if (val === 'ditolak') card.style.borderColor = '#c53030';
                            card.style.background = '#f7fafc';
                        } else {
                            card.style.borderColor = '#e2e8f0';
                            card.style.background = '#fff';
                        }
                    });
                };
                radios.forEach(radio => radio.addEventListener('change', updateBorders));
                updateBorders();
            });
        </script>

        @if(in_array($report->status, ['valid', 'diproses', 'selesai']))
        <div>
            <h2 class="section-title">Petugas Tersedia</h2>

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
        @endif

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
</html>y>
</html>