<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>Profil Saya - Palapa</title>
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
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f4f7f9;
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
            background: var(--surface);
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .back-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #1f76c2;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 18px;
            transition: background 0.2s;
        }
        
        .back-btn:hover {
            background: #165f9e;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
            color: #1f76c2;
            font-weight: 800;
        }

        .profile-banner {
            background: linear-gradient(135deg, #71b4e1, #5398cf);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 24px;
            position: relative;
            color: white;
        }

        .profile-banner .edit-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
        }

        .profile-banner .edit-btn:hover {
            background: rgba(255,255,255,0.1);
        }

        .banner-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
        }

        .banner-info h2 {
            margin: 0 0 8px;
            font-size: 28px;
            font-weight: 700;
        }

        .banner-info p {
            margin: 0 0 4px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            opacity: 0.9;
        }

        .grid-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #2a2e38;
            margin: 0 0 16px;
        }

        .filters {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
        }

        .filter-btn {
            padding: 8px 16px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e5eaf1;
            color: #687385;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            font-family: inherit;
            text-decoration: none;
        }

        .filter-btn:hover {
            background: #edf2f7;
        }

        .filter-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .search-box {
            padding: 8px 16px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e5eaf1;
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            max-width: 250px;
        }

        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            font-family: inherit;
            font-size: 13px;
            width: 100%;
        }

        .table-wrap {
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            background: #fcfdff;
            color: #687385;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            text-align: left;
            padding: 12px 16px;
            border-bottom: 1px solid var(--line);
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--line);
            color: #2a2e38;
            font-weight: 500;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge.proses {
            background: #fff8e1;
            color: #d49524;
        }

        .badge.selesai {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .updates-panel {
            border-left: 1px solid var(--line);
            padding-left: 24px;
        }

        .updates-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .update-item {
            padding: 14px 0;
            border-bottom: 1px solid var(--line);
        }

        .update-item:last-child {
            border-bottom: none;
        }

        .update-content {
            font-size: 12px;
            color: #4a5568;
            line-height: 1.5;
            margin: 0 0 8px;
        }

        .update-meta {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: var(--muted);
        }

        @media (max-width: 1024px) {
            .grid-layout {
                grid-template-columns: 1fr;
            }
            .updates-panel {
                border-left: none;
                border-top: 1px solid var(--line);
                padding-left: 0;
                padding-top: 24px;
            }
        }

        @media (max-width: 768px) {
            .layout {
                flex-direction: column;
            }
            .content {
                width: 100%;
                max-width: none !important;
                padding: 16px;
            }
            .profile-banner {
                flex-direction: column;
                text-align: center;
                gap: 16px;
            }
            .filters {
                flex-wrap: wrap;
            }
            .search-box {
                max-width: none;
            }
            .table-wrap {
                overflow-x: auto;
            }
        }

        [x-cloak] { display: none !important; }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Modal Backdrop */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        /* Modal Badges overrides */
        .modal-content .badge.pending { background: #fff3d5; color: #996d00; }
        .modal-content .badge.valid { background: #e6f1ff; color: #1f76c2; }
        .modal-content .badge.ditolak { background: #fed7d7; color: #c53030; }
        .modal-content .badge.diproses { background: #e0f2fe; color: #0369a1; }
        .modal-content .badge.selesai { background: #dcfce7; color: #166534; }
    </style>
</head>
<body>
<div class="layout" x-data="{ 
    sidebarOpen: true, 
    modalOpen: false, 
    editModalOpen: false,
    loading: false,
    reportTitle: '',
    statusHistories: [],
    fetchHistory(reportId) {
        this.modalOpen = true;
        this.loading = true;
        this.statusHistories = [];
        this.reportTitle = '';
        
        fetch(`/reports/${reportId}/history`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            this.reportTitle = data.report.title;
            this.statusHistories = data.statusHistories;
            this.loading = false;
        })
        .catch(err => {
            console.error('Error fetching history:', err);
            this.loading = false;
        });
    }
}">
    @include('components.sidebar')

    <main class="content" :style="sidebarOpen ? 'max-width: calc(100vw - 306px);' : 'max-width: calc(100vw - 138px);'">
        <div class="header">
            <a href="{{ route('beranda') }}" class="back-btn"><i class="ph ph-arrow-left"></i></a>
            <h1>Profil saya</h1>
        </div>

        <div class="profile-banner">
            <img src="https://i.pravatar.cc/150?img=12" alt="Avatar" class="banner-avatar">
            <div class="banner-info">
                <h2>{{ auth()->check() ? auth()->user()->users_name : 'John Smith' }}</h2>
                @if(auth()->check() && auth()->user()->email)
                <p><i class="ph ph-envelope"></i> {{ auth()->user()->email }}</p>
                @endif
                <p><i class="ph ph-phone"></i> {{ auth()->check() ? (auth()->user()->phone ?? 'Belum diisi') : '628102022300' }}</p>
            </div>
            <button class="edit-btn" @click="editModalOpen = true"><i class="ph ph-pencil-simple"></i></button>
        </div>

        @if(session('success'))
            <div style="background: #c6f6d5; color: #2f855a; padding: 12px; border-radius: 8px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-top: 16px;">
                <i class="ph ph-check-circle" style="font-size: 20px;"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid-layout">
            <div class="history-section">
                <h3 class="section-title">Laporan Saya</h3>
                <span style="display: none;">Riwayat Laporan</span>
                
                <div class="filters-container" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
                    <div class="filters" style="margin-bottom: 0; display: flex; gap: 8px; flex-wrap: wrap;">
                        <a href="{{ route('profile', ['status' => 'semua', 'search' => $currentSearch]) }}" class="filter-btn {{ $currentStatus === 'semua' ? 'active' : '' }}">Semua</a>
                        <a href="{{ route('profile', ['status' => 'pending', 'search' => $currentSearch]) }}" class="filter-btn {{ $currentStatus === 'pending' ? 'active' : '' }}">Pending</a>
                        <a href="{{ route('profile', ['status' => 'valid', 'search' => $currentSearch]) }}" class="filter-btn {{ $currentStatus === 'valid' ? 'active' : '' }}">Valid</a>
                        <a href="{{ route('profile', ['status' => 'ditolak', 'search' => $currentSearch]) }}" class="filter-btn {{ $currentStatus === 'ditolak' ? 'active' : '' }}">Ditolak</a>
                        <a href="{{ route('profile', ['status' => 'diproses', 'search' => $currentSearch]) }}" class="filter-btn {{ $currentStatus === 'diproses' ? 'active' : '' }}">Diproses</a>
                        <a href="{{ route('profile', ['status' => 'selesai', 'search' => $currentSearch]) }}" class="filter-btn {{ $currentStatus === 'selesai' ? 'active' : '' }}">Selesai</a>
                    </div>

                    <form class="search-form" method="GET" action="{{ route('profile') }}" style="display: flex; gap: 8px; align-items: center;">
                        @if($currentStatus !== 'semua')
                            <input type="hidden" name="status" value="{{ $currentStatus }}">
                        @endif
                        <div class="search-box" style="margin-bottom: 0;">
                            <i class="ph ph-magnifying-glass" style="color: #a0aec0;"></i>
                            <input type="text" name="search" value="{{ $currentSearch }}" placeholder="Cari judul, lokasi, atau tanggal...">
                        </div>
                        <button type="submit" style="background: #1f76c2; color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 13px; font-weight: 600; cursor: pointer; font-family: inherit;">Cari</button>
                    </form>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul</th>
                                <th>Lokasi</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Foto</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $report)
                                <tr>
                                    <td>#{{ $report->report_id }}</td>
                                    <td>{{ $report->title }}</td>
                                    <td>
                                        @if($report->address)
                                            {{ \Illuminate\Support\Str::limit($report->address, 50) }}
                                        @else
                                            {{ $report->latitude }}, {{ $report->longitude }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($report->fire_level)
                                            @php
                                                $levelColors = [
                                                    'low' => 'background: #edf2f7; color: #4a5568;',
                                                    'medium' => 'background: #fffaf0; color: #dd6b20;',
                                                    'high' => 'background: #fff5f5; color: #c53030;',
                                                    'critical' => 'background: #ffebeb; color: #9b2c2c; border: 1px solid #9b2c2c;'
                                                ];
                                                $levelLabels = [
                                                    'low' => 'Low',
                                                    'medium' => 'Medium',
                                                    'high' => 'High',
                                                    'critical' => 'Critical'
                                                ];
                                            @endphp
                                            <span class="badge" style="{{ $levelColors[$report->fire_level] ?? '' }} font-size: 10px; padding: 2px 8px;">
                                                {{ $levelLabels[$report->fire_level] ?? ucfirst($report->fire_level) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if(strtolower($report->status) == 'selesai')
                                            <span class="badge selesai"><i class="ph ph-check-circle"></i> Selesai</span>
                                        @elseif(strtolower($report->status) == 'ditolak')
                                            <span class="badge" style="background: #fed7d7; color: #c53030;"><i class="ph ph-x-circle"></i> Ditolak</span>
                                            @if($report->rejection_reason)
                                                <div style="font-size: 11px; color: #c53030; margin-top: 4px; max-width: 150px; line-height: 1.3;">{{ $report->rejection_reason }}</div>
                                            @endif
                                        @elseif(strtolower($report->status) == 'pending')
                                            <span class="badge proses" style="background: #fff3d5; color: #996d00;"><i class="ph ph-clock"></i> Pending</span>
                                        @elseif(strtolower($report->status) == 'valid')
                                            <span class="badge proses" style="background: #e6f1ff; color: #1f76c2;"><i class="ph ph-check"></i> Valid</span>
                                        @else
                                            <span class="badge proses"><i class="ph ph-clock"></i> Diproses</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($report->photo)
                                            <a class="photo-link" style="color: #1f76c2; text-decoration: none; font-weight: 600;" href="{{ route('reports.photo', ['path' => $report->photo]) }}" target="_blank">Lihat foto</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $report->created_at?->format('d/m/Y') }}</td>
                                    <td style="white-space: nowrap;">
                                        <a href="{{ route('reports.history', $report->report_id) }}" @click.prevent="fetchHistory({{ $report->report_id }})" style="text-decoration: none; display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; background: #f1f4f8; color: #607089; border-radius: 6px; font-weight: 600; font-size: 11px; margin-right: 4px;">
                                            Riwayat
                                        </a>
                                        @if(strtolower($report->status) == 'pending' || strtolower($report->status) == 'diproses')
                                            <a href="{{ route('reports.edit', $report->report_id) }}" style="text-decoration: none; display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; background: #e6f1ff; color: #1f76c2; border-radius: 6px; font-weight: 600; font-size: 11px;">
                                                <i class="ph ph-pencil-simple"></i> Edit
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align: center; color: #8a94a5; padding: 24px;">Belum ada riwayat laporan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

<!-- Modal Edit Profil -->
<div x-show="editModalOpen"
     class="modal-backdrop"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.self="editModalOpen = false"
     x-cloak>
     
    <div class="modal-content" 
         style="background: #ffffff; width: 100%; max-width: 500px; border-radius: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; overflow: hidden; position: relative; border: 1px solid #e5eaf1;"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4">
         
        <!-- Header -->
        <div style="padding: 20px 24px; border-bottom: 1px solid #edf1f6; display: flex; align-items: center; justify-content: space-between;">
            <h3 style="margin: 0; font-size: 20px; font-weight: 800; color: #1f76c2;">Edit Profil</h3>
            <button @click="editModalOpen = false" 
                    style="border: none; background: #f1f4f8; color: #607089; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; font-size: 18px;"
                    onmouseover="this.style.background='#e2e8f0'; this.style.color='#2a2e38'"
                    onmouseout="this.style.background='#f1f4f8'; this.style.color='#607089'">
                <i class="ph ph-x"></i>
            </button>
        </div>
        
        <!-- Form Body -->
        <form action="{{ route('profile.update') }}" method="POST" style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
            @csrf
            @method('PUT')
            
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label for="users_name" style="font-size: 13px; font-weight: 600; color: #4a5568;">Nama Lengkap</label>
                <input type="text" id="users_name" name="users_name" value="{{ auth()->check() ? auth()->user()->users_name : '' }}" required
                       style="padding: 12px 16px; border-radius: 8px; border: 1px solid #e5eaf1; outline: none; font-family: inherit; font-size: 14px; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='#1f76c2'" onblur="this.style.borderColor='#e5eaf1'">
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label for="phone" style="font-size: 13px; font-weight: 600; color: #4a5568;">Nomor Telepon</label>
                <input type="text" id="phone" name="phone" value="{{ auth()->check() ? auth()->user()->phone : '' }}" placeholder="Contoh: 08123456789"
                       style="padding: 12px 16px; border-radius: 8px; border: 1px solid #e5eaf1; outline: none; font-family: inherit; font-size: 14px; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='#1f76c2'" onblur="this.style.borderColor='#e5eaf1'">
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 16px;">
                <button type="button" @click="editModalOpen = false" 
                        style="padding: 10px 20px; border-radius: 8px; background: white; border: 1px solid #e5eaf1; color: #4a5568; font-weight: 600; cursor: pointer; font-family: inherit;">
                    Batal
                </button>
                <button type="submit" 
                        style="padding: 10px 20px; border-radius: 8px; background: #1f76c2; border: none; color: white; font-weight: 600; cursor: pointer; font-family: inherit; box-shadow: 0 4px 12px rgba(31, 118, 194, 0.2);">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Riwayat Status -->
<div x-show="modalOpen"
     class="modal-backdrop"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.self="modalOpen = false"
     x-cloak>
     
    <div class="modal-content" 
         style="background: #ffffff; width: 100%; max-width: 600px; max-height: 85vh; border-radius: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); display: flex; flex-direction: column; overflow: hidden; position: relative; border: 1px solid #e5eaf1;"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         @click.outside="modalOpen = false">
         
        <!-- Header -->
        <div style="padding: 20px 24px; border-bottom: 1px solid #edf1f6; display: flex; align-items: center; justify-content: space-between; gap: 16px;">
            <div>
                <h3 style="margin: 0; font-size: 20px; font-weight: 800; color: #1f76c2;">Riwayat Status</h3>
                <p style="margin: 4px 0 0; font-size: 13px; color: #8a94a5; font-weight: 500;" x-text="reportTitle || 'Memuat...'"></p>
            </div>
            <button @click="modalOpen = false" 
                    style="border: none; background: #f1f4f8; color: #607089; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; font-size: 18px;"
                    onmouseover="this.style.background='#e2e8f0'; this.style.color='#2a2e38'"
                    onmouseout="this.style.background='#f1f4f8'; this.style.color='#607089'">
                <i class="ph ph-x"></i>
            </button>
        </div>
        
        <!-- Content Body -->
        <div style="flex: 1; overflow-y: auto; padding: 24px; min-height: 200px;">
            <!-- Loading State -->
            <div x-show="loading" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 200px; gap: 12px;">
                <div class="spinner" style="width: 40px; height: 40px; border: 4px solid rgba(31, 118, 194, 0.1); border-top-color: #1f76c2; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <p style="margin: 0; font-size: 13px; color: #8a94a5; font-weight: 500;">Memuat riwayat...</p>
            </div>
            
            <!-- Empty State -->
            <div x-show="!loading && statusHistories.length === 0" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 200px; gap: 12px; text-align: center;">
                <i class="ph ph-clock-counter-clockwise" style="font-size: 48px; color: #8a94a5;"></i>
                <p style="margin: 0; font-size: 13px; color: #8a94a5; font-weight: 500;">Belum ada riwayat status.</p>
            </div>
            
            <!-- Timeline -->
            <ul x-show="!loading && statusHistories.length > 0" 
                class="modal-timeline" 
                style="position: relative; padding-left: 20px; list-style: none; margin: 0;">
                
                <template x-for="(history, index) in statusHistories" :key="history.id">
                    <li class="modal-timeline-item" style="position: relative; padding-left: 32px; margin-bottom: 24px;">
                        <!-- Connector line -->
                        <div x-show="index < statusHistories.length - 1" 
                             style="content: ''; position: absolute; top: 16px; bottom: -24px; left: 8px; width: 2px; background: #e5eaf1;"></div>
                             
                        <!-- Bullet marker -->
                        <div style="position: absolute; left: 3px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: #1f76c2; border: 2px solid #fff; box-shadow: 0 0 0 4px #e5eaf1; z-index: 1;"></div>
                        
                        <!-- Timestamp -->
                        <div style="font-size: 11px; color: #8a94a5; margin-bottom: 6px; font-weight: 600;" x-text="history.formatted_date"></div>
                        
                        <!-- Content Card -->
                        <div style="background: #f8fbff; border: 1px solid #e5eaf1; border-radius: 12px; padding: 14px 16px;">
                            <h4 style="font-size: 14px; font-weight: 700; color: #2a2e38; margin: 0 0 6px 0; display: flex; align-items: center; flex-wrap: wrap; gap: 8px;">
                                Status berubah menjadi
                                <span :class="'badge ' + history.status_baru.toLowerCase()" 
                                      style="display: inline-flex; align-items: center; border-radius: 999px; font-size: 11px; font-weight: 700; padding: 2px 8px; text-transform: lowercase;"
                                      x-text="history.status_baru"></span>
                            </h4>
                            <p style="font-size: 13px; color: #607089; margin: 0 0 8px 0; line-height: 1.4;" x-text="history.catatan"></p>
                            <div style="font-size: 11px; color: #8a94a5; display: flex; align-items: center; gap: 4px;">
                                <i class="ph ph-user" style="font-size: 12px;"></i> Oleh: <span x-text="history.diubah_oleh"></span>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>
</div>
</body>
</html>
