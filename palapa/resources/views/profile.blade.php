<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            grid-template-columns: 1fr 320px;
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
    </style>
</head>
<body>
<div class="layout" x-data="{ sidebarOpen: true }">
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
                <p><i class="ph ph-envelope"></i> {{ auth()->check() ? auth()->user()->email : 'johnsm1th@gmail.com' }}</p>
                <p><i class="ph ph-phone"></i> 628102022300</p>
            </div>
            <button class="edit-btn"><i class="ph ph-pencil-simple"></i></button>
        </div>

        <div class="grid-layout">
            <div class="history-section">
                <h3 class="section-title">Riwayat Laporan</h3>
                
                <div class="filters">
                    <button class="filter-btn"><i class="ph ph-clock"></i> Tanggal <i class="ph ph-caret-down"></i></button>
                    <button class="filter-btn"><i class="ph ph-flag"></i> Status <i class="ph ph-caret-down"></i></button>
                    <div class="search-box">
                        <i class="ph ph-magnifying-glass" style="color: #a0aec0;"></i>
                        <input type="text" placeholder="Cari Lokasi">
                    </div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>STATUS</th>
                                <th>LOKASI</th>
                                <th>TANGGAL PELAPORAN</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $report)
                                <tr>
                                    <td>#{{ $report->id }}</td>
                                    <td>
                                        @if(strtolower($report->status) == 'selesai')
                                            <span class="badge selesai"><i class="ph ph-check-circle"></i> Selesai</span>
                                        @else
                                            <span class="badge proses"><i class="ph ph-clock"></i> Diproses</span>
                                        @endif
                                    </td>
                                    <td>{{ $report->latitude }}, {{ $report->longitude }}</td>
                                    <td>{{ $report->created_at?->format('d/m/Y') }}</td>
                                    <td>
                                        @if(strtolower($report->status) == 'pending' || strtolower($report->status) == 'diproses')
                                            <a href="{{ route('reports.edit', $report->report_id) }}" style="text-decoration: none; display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; background: #e6f1ff; color: #1f76c2; border-radius: 6px; font-weight: 600; font-size: 11px;">
                                                <i class="ph ph-pencil-simple"></i> Edit
                                            </a>
                                        @else
                                            <i class="ph ph-caret-down" style="color: #a0aec0; cursor: pointer;"></i>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #8a94a5;">Belum ada riwayat laporan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="updates-panel">
                <div class="updates-header">
                    <h3 class="section-title" style="margin: 0;">Pembaharuan</h3>
                    <i class="ph ph-bell" style="color: #1f76c2; font-size: 20px;"></i>
                </div>
                
                <div class="update-item">
                    <p class="update-content">Hai, {{ auth()->check() ? explode(' ', auth()->user()->users_name)[0] : 'John' }}! Laporan kamu sedang ditangani oleh petugas pemadam. Yuk pantau!</p>
                    <div class="update-meta">
                        <span>Laporan #32</span>
                        <span style="color: #1f76c2; font-weight: 600;">Baru</span>
                    </div>
                </div>
                
                <div class="update-item">
                    <p class="update-content">Hai, {{ auth()->check() ? explode(' ', auth()->user()->users_name)[0] : 'John' }}! Terima kasih. Laporan kamu sudah diverifikasi dan akan segera ditangani.</p>
                    <div class="update-meta">
                        <span>Laporan #45</span>
                        <span>12:42</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
