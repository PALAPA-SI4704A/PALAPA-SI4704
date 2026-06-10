<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>Detail Laporan Admin - Palapa</title>
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

        .badge-pending { background: #e6f0fd; color: #3182ce; }
        .badge-diproses { background: #fefcbf; color: #b7791f; }
        .badge-selesai { background: #c6f6d5; color: #2f855a; }
        .badge-valid { background: #e2fbf0; color: #2b6cb0; }
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
            color: #3182ce;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
            padding: 0;
            font-family: inherit;
            transition: color 0.2s;
        }
        
        .btn-action:hover {
            color: var(--primary-dark);
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

        .verification-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 24px;
        }

        .btn-verify {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            padding: 16px 20px;
            border-radius: 16px;
            border: none;
            cursor: pointer;
            text-align: left;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-verify .btn-text {
            display: flex;
            flex-direction: column;
        }

        .btn-verify .btn-text strong {
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
        }

        .btn-verify .btn-text span {
            font-size: 12px;
            opacity: 0.85;
            font-weight: 400;
            font-family: 'Poppins', sans-serif;
            margin-top: 2px;
        }

        .btn-accept {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .btn-accept:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.35);
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        .btn-reject {
            background: white;
            color: #ef4444;
            border: 2px solid #fee2e2;
        }

        .btn-reject:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.15);
            border-color: #ef4444;
            background: #fef2f2;
        }

        .report-grid {
            display: grid;
            grid-template-columns: 1.7fr 1.3fr;
            gap: 24px;
            align-items: start;
            width: 100%;
        }
        
        .col-main, .col-sidebar {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        @media (max-width: 1200px) {
            .report-grid {
                grid-template-columns: 1fr;
            }
        }

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
        
        <!-- Flash Message -->
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

        <div class="report-grid">
            <div class="col-main">
                <div class="main-panel">
                    <div class="report-header" style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <h1 class="report-title">{{ $report->title ?? 'Laporan Titik Api' }}</h1>
                            <p class="report-subtitle">#{{ $report->report_id }} Detail Laporan</p>
                        </div>
                        <form action="{{ route('admin.reports.destroy', $report->report_id) }}" method="POST" @submit.prevent="$dispatch('open-confirm-modal', { message: 'Apakah Anda yakin ingin menghapus laporan ini?', form: $event.target })" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: white; color: #e53e3e; border: 1px solid #e53e3e; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; box-shadow: 0 1px 2px rgba(229, 62, 62, 0.1);" onmouseover="this.style.background='#fff5f5'" onmouseout="this.style.background='white'">
                                <i class="ph ph-trash" style="font-size: 18px;"></i> Hapus Laporan
                            </button>
                        </form>
                    </div>

                    <div class="report-details-grid">
                        <div class="detail-box">
                            <span class="detail-label">STATUS</span>
                            @php
                                $statusClass = 'badge-pending';
                                if($report->status == 'diproses') $statusClass = 'badge-diproses';
                                if($report->status == 'selesai') $statusClass = 'badge-selesai';
                                if($report->status == 'valid') $statusClass = 'badge-valid';
                                if($report->status == 'ditolak') $statusClass = 'badge-ditolak';
                            @endphp
                            <span class="badge {{ $statusClass }}">
                                @if($report->status == 'pending')
                                    🕒 Menunggu Verifikasi
                                @else
                                    {{ ucfirst($report->status) }}
                                @endif
                            </span>
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
            </div>

            <div class="col-sidebar">
                <!-- Form Verifikasi (Hanya Tampil Jika Status 'pending') -->
                @if($report->status === 'pending')
                <div class="main-panel" style="width: 100%;" x-data="{ showRejectForm: false }">
                    <h2 class="section-title" style="font-size: 20px;">Verifikasi Laporan</h2>
                    <p style="margin-bottom: 16px; color: #718096; font-size: 13px;">Tentukan validitas laporan masuk ini sebelum ditugaskan kepada petugas di lapangan.</p>
                    
                    <div class="verification-actions" x-show="!showRejectForm" style="grid-template-columns: 1fr;">
                        <form action="{{ route('admin.reports.verify', $report->report_id) }}" method="POST" style="margin: 0; display: flex; width: 100%;">
                            @csrf
                            <input type="hidden" name="status" value="valid">
                            <button type="submit" class="btn-verify btn-accept" style="width: 100%; padding: 12px 16px;">
                                <i class="ph-fill ph-check-circle" style="font-size: 24px;"></i>
                                <div class="btn-text">
                                    <strong>Terima & Validasi</strong>
                                </div>
                            </button>
                        </form>
                        <button type="button" @click="showRejectForm = true" class="btn-verify btn-reject" style="width: 100%; padding: 12px 16px; margin-top: 12px;">
                            <i class="ph-fill ph-x-circle" style="font-size: 24px;"></i>
                            <div class="btn-text">
                                <strong>Tolak Laporan</strong>
                            </div>
                        </button>
                    </div>

                    <div x-show="showRejectForm" style="display: none; background: #fff5f5; padding: 16px; border-radius: 8px; border: 1px solid #fed7d7; margin-top: 12px;">
                        <form action="{{ route('admin.reports.verify', $report->report_id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="ditolak">
                            <div style="margin-bottom: 12px;">
                                <label style="display: block; font-size: 13px; font-weight: 600; color: #c53030; margin-bottom: 8px;">Alasan Penolakan</label>
                                <textarea name="rejection_reason" required rows="3" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0; font-family: inherit; font-size: 13px;" placeholder="Masukkan alasan mengapa laporan ini ditolak..."></textarea>
                            </div>
                            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                <button type="submit" style="background: #e53e3e; color: white; border: none; padding: 8px 14px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 13px;">
                                    Konfirmasi Tolak
                                </button>
                                <button type="button" @click="showRejectForm = false" style="background: #cbd5e0; color: #4a5568; border: none; padding: 8px 14px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 13px;">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

        <!-- Riwayat / Status Penugasan Petugas -->
        @if($report->penugasans->isNotEmpty())
        <div class="main-panel" style="width: 100%;">
            <h2 class="section-title" style="font-size: 20px;">Status Penugasan Petugas</h2>
            <p style="margin-bottom: 16px; color: #718096; font-size: 13px;">Daftar petugas yang ditugaskan beserta status pekerjaannya.</p>

            <div style="overflow-x: auto;">
                <table style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Petugas</th>
                            <th>Status</th>
                            <th>Aksi / Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report->penugasans as $penugasan)
                        <tr>
                            <td>
                                <div class="petugas-info" style="font-size: 12px; gap: 6px;">
                                    {{ $penugasan->petugas?->users_name }}
                                </div>
                            </td>
                            <td>
                                @if($penugasan->completed_at)
                                    <span class="badge badge-selesai" style="font-size: 10px; padding: 3px 6px;">Selesai</span>
                                @else
                                    <span class="badge badge-diproses" style="font-size: 10px; padding: 3px 6px;">Sedang Bertugas</span>
                                @endif
                            </td>
                            <td>
                                @if($penugasan->bukti_photo)
                                    <a href="{{ route('reports.photo', ['path' => $penugasan->bukti_photo]) }}" target="_blank" class="btn-link" style="display: inline-flex; align-items: center; gap: 4px; font-size: 11px;">
                                        <i class="ph ph-image"></i> Lihat Bukti Foto
                                    </a>
                                @else
                                    <span style="color: #a0aec0; font-style: italic; font-size: 11px;">Belum ada foto</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Penugasan Petugas (Tampil Jika Status 'valid', 'diproses', atau 'selesai') -->
        @if(in_array($report->status, ['valid', 'diproses', 'selesai']))
        <div class="main-panel" style="width: 100%;" x-data="{ showOnDuty: false }">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 16px;">
                <div>
                    <h2 class="section-title" style="font-size: 20px; margin: 0 0 4px 0;">Petugas Tersedia</h2>
                    <p style="color: #718096; font-size: 13px; margin: 0;">Tugaskan petugas lapangan untuk menangani kebakaran ini berdasarkan pos penempatan terdekat mereka.</p>
                </div>
                
                <!-- Toggle button -->
                <div style="display: flex; align-items: center; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 9999px; padding: 6px 12px; cursor: pointer; user-select: none; transition: all 0.2s;" @click="showOnDuty = !showOnDuty" title="Klik untuk menampilkan/menyembunyikan petugas yang sedang sibuk">
                    <i class="ph" :class="showOnDuty ? 'ph-eye' : 'ph-eye-slash'" style="font-size: 16px; margin-right: 6px; color: #475569;"></i>
                    <span style="font-size: 11px; font-weight: 600; color: #475569;" x-text="showOnDuty ? 'Sembunyikan On Duty' : 'Tampilkan On Duty'"></span>
                </div>
            </div>

            @forelse($petugasTersedia as $posName => $petugasGroup)
            <h3 style="color: #2d3748; font-size: 14px; font-weight: 700; margin-top: 20px; margin-bottom: 12px; border-bottom: 2px solid #edf2f7; padding-bottom: 8px;">
                <i class="ph ph-map-pin" style="color: #e53e3e;"></i> {{ $posName }}
                <span style="font-size: 11px; color: #718096; font-weight: 500; margin-left: 8px;">({{ $petugasGroup->count() }} Petugas)</span>
            </h3>
            <div style="overflow-x: auto; margin-bottom: 24px;">
                <table style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Jarak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $hasAvailable = false;
                            foreach ($petugasGroup as $p) {
                                $busy = \App\Models\Penugasan::where('petugas_id', $p->users_id)->whereNull('completed_at')->exists();
                                if (!$busy) {
                                    $hasAvailable = true;
                                    break;
                                }
                            }
                        @endphp

                        @if(!$hasAvailable && $petugasGroup->isNotEmpty())
                        <tr x-show="!showOnDuty">
                            <td colspan="4" style="text-align: center; color: #718096; font-size: 11px; padding: 24px; background: #fffaf0; border-radius: 8px;">
                                <i class="ph ph-info" style="font-size: 16px; vertical-align: middle; margin-right: 4px; color: #dd6b20;"></i>
                                Semua petugas di pos ini sedang bertugas (On Duty). Aktifkan <strong>"Tampilkan On Duty"</strong> untuk melihat mereka.
                            </td>
                        </tr>
                        @endif

                        @foreach($petugasGroup as $petugas)
                            @php
                                $isAssigned = \App\Models\Penugasan::where('petugas_id', $petugas->users_id)
                                                ->whereNull('completed_at')
                                                ->exists();
                            @endphp
                        <tr x-show="showOnDuty || !{{ $isAssigned ? 'true' : 'false' }}">
                            <td>
                                <div class="petugas-info">
                                    <div class="petugas-avatar">👩‍🚒</div>
                                    {{ $petugas->users_name }}
                                </div>
                            </td>
                            <td>
                                @if($isAssigned)
                                    <span class="badge badge-onduty" style="font-size: 9px; padding: 2px 6px;">On Duty</span>
                                @else
                                    <span class="badge badge-available" style="font-size: 9px; padding: 2px 6px;">Available</span>
                                @endif
                            </td>
                            <td><span style="font-size: 11px; color: #4a5568;">{{ $petugas->distance !== null ? $petugas->distance . ' km' : '-' }}</span></td>
                            <td>
                                @php
                                    $currentlyAssigned = \App\Models\Penugasan::where('report_id', $report->report_id)
                                                            ->where('petugas_id', $petugas->users_id)
                                                            ->whereNull('completed_at')
                                                            ->exists();
                                @endphp
                                @if($report->status === 'valid')
                                    @if($isAssigned)
                                        <span style="font-weight:600; color:#e53e3e; font-size:11px;">On Duty</span>
                                    @else
                                        <form action="{{ route('admin.reports.assign', ['report' => $report->report_id, 'petugas' => $petugas->users_id]) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            <button type="submit" class="btn-action" style="font-size: 11px;">[Tugaskan]</button>
                                        </form>
                                    @endif
                                @elseif($report->status === 'diproses')
                                    @if($currentlyAssigned)
                                        <span style="font-weight:600; color:#b7791f; font-size:11px;">Bertugas</span>
                                    @elseif($isAssigned)
                                        <span style="font-weight:600; color:#e53e3e; font-size:11px;">On Duty</span>
                                    @else
                                        <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-start;">
                                            <form action="{{ route('admin.reports.assign', ['report' => $report->report_id, 'petugas' => $petugas->users_id]) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="submit" class="btn-action" style="font-size: 11px;">[Tugaskan +]</button>
                                            </form>
                                            <form action="{{ route('admin.reports.reassign', ['report' => $report->report_id, 'petugas' => $petugas->users_id]) }}" method="POST" style="margin: 0;" @submit.prevent="$dispatch('open-confirm-modal', { message: 'Apakah Anda yakin ingin mengubah penugasan ke petugas ini?', form: $event.target })">
                                                @csrf
                                                <button type="submit" class="btn-action" style="color: #dd6b20; font-size: 11px;">[Ubah]</button>
                                            </form>
                                        </div>
                                    @endif
                                @else
                                    <span style="color:#a0aec0; font-size:11px;">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @empty
                <div style="text-align: center; color: #a0aec0; padding: 24px; background: #f8fafc; border-radius: 12px; border: 1px dashed #e2e8f0; font-size: 12px;">
                    Tidak ada petugas lapangan yang terdaftar.
                </div>
            @endforelse
        </div>
        @endif
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

        L.marker([lat, lng], {
            icon: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            })
        }).addTo(map)
            .bindPopup("<b>Lokasi Kejadian</b><br>{{ $report->latitude }}, {{ $report->longitude }}")
            .openPopup();
            
        @if(in_array($report->status, ['valid', 'diproses', 'selesai']))
            // Tampilkan titik Pos Pemadam
            const posPemadam = {!! json_encode($posPemadam) !!};
            for (const [posName, coords] of Object.entries(posPemadam)) {
                L.marker([coords.lat, coords.lng], {
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    }),
                    zIndexOffset: 1000
                }).addTo(map)
                .bindPopup("<b>" + posName + "</b><br>Basis Pos Pemadam");
            }

            // Tampilkan titik Petugas (flatten array dari grouped collection)
            const petugasList = {!! json_encode($petugasTersedia->flatten()) !!};
            petugasList.forEach(function(petugas) {
                if(petugas.latitude && petugas.longitude) {
                    const markerColor = petugas.is_busy ? 'orange' : 'blue';
                    const statusLabel = petugas.is_busy ? 'On Duty' : 'Available';
                    L.marker([petugas.latitude, petugas.longitude], {
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-' + markerColor + '.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        })
                    }).addTo(map)
                    .bindPopup("<b>Petugas: " + petugas.users_name + " (" + statusLabel + ")</b><br>Pos: " + petugas.assigned_pos + "<br>Jarak: " + (petugas.distance !== null ? petugas.distance + " km" : "-"));
                }
            });
        @endif
    });
</script>
</body>
</html>
