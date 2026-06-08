<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>Manajemen Pengguna - Admin Palapa</title>
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

        .filter-group.search-box {
            flex: 1;
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

        .filter-input {
            border: none;
            font-size: 13px;
            font-family: inherit;
            background: transparent;
            outline: none;
            color: #4a5568;
            width: 100%;
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
            min-width: 90px;
        }

        .badge-admin { background: #fed7d7; color: #c53030; }
        .badge-petugas { background: #fefcbf; color: #b7791f; }
        .badge-masyarakat { background: #e6f0fd; color: #3182ce; }

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

        .pagination-container {
            margin-top: 24px;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        /* Mengatur ukuran Ikon SVG di pagination */
        .pagination-container svg {
            width: 18px;
            height: 18px;
        }

        /* Menyembunyikan tampilan mobile default Laravel yang menumpuk */
        .pagination-container nav > div.sm\:hidden {
            display: none;
        }
        
        /* Layout untuk kontainer halaman (1, 2, 3...) */
        .pagination-container nav > div.hidden.sm\:flex-1.sm\:flex {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }
        
        /* Teks "Showing 1 to 10 of 20 results" */
        .pagination-container p.text-sm {
            font-size: 13px;
            color: #718096;
            margin: 0;
        }

        /* Kontainer deretan angka halaman */
        .pagination-container .relative.z-0.inline-flex {
            display: inline-flex;
            gap: 6px;
            box-shadow: none !important;
            flex-wrap: wrap;
            justify-content: center;
        }

        /* Style untuk setiap kotak angka halaman dan panah */
        .pagination-container .relative.z-0.inline-flex > a,
        .pagination-container .relative.z-0.inline-flex > span {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 14px !important;
            border-radius: 8px !important;
            border: 1px solid #e2e8f0 !important;
            background: white;
            color: #4a5568;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            margin: 0 !important;
            box-shadow: none !important;
        }

        /* Efek hover pada kotak angka */
        .pagination-container .relative.z-0.inline-flex > a:hover {
            background: #f8fafc;
            color: var(--primary);
            border-color: #cbd5e0 !important;
        }

        /* Style untuk angka halaman yang sedang aktif */
        .pagination-container .relative.z-0.inline-flex > span[aria-current="page"] > span,
        .pagination-container .relative.z-0.inline-flex > span[aria-current="page"] {
            background: var(--primary) !important;
            color: white !important;
            border-color: var(--primary) !important;
            cursor: default;
        }

        /* Reset padding & border pada span di dalam span (bawaan Laravel) agar tidak bertumpuk ganda */
        .pagination-container .relative.z-0.inline-flex > span > span {
            border: none !important;
            padding: 0 !important;
            background: transparent !important;
        }

        .tabs-container {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            border-bottom: 2px solid #edf2f7;
            padding-bottom: 0px;
        }

        .tab-btn {
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            color: #718096;
            text-decoration: none;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
            margin-bottom: -2px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .tab-btn:hover {
            color: var(--primary);
        }

        .tab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        @media (max-width: 980px) {
            .layout { flex-direction: column; }
            .content { max-width: none !important; }
            .filters { flex-direction: column; align-items: stretch; gap: 12px; }
            .table-responsive { overflow-x: auto; }
            .tabs-container { flex-direction: row; flex-wrap: wrap; }
        }
    </style>
</head>
<body>
<div class="layout" x-data="{ sidebarOpen: true }">
    @include('components.sidebar')

    <main class="content" :style="sidebarOpen ? 'max-width: calc(100vw - 306px);' : 'max-width: calc(100vw - 138px);'">
        
        <!-- Flash Message -->
        @if(session('success'))
            <div style="background: #c6f6d5; color: #2f855a; padding: 14px 20px; border-radius: 12px; font-weight: 600; display: flex; align-items: center; gap: 10px; border: 1px solid #b2f5ea; margin-bottom: 8px;">
                <i class="ph ph-check-circle" style="font-size: 22px;"></i>
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('import_summary'))
            @php $summary = session('import_summary'); @endphp
            
            {{-- Bagian Preview Data Berhasil --}}
            @if(isset($summary['imported_data']) && count($summary['imported_data']) > 0)
                <div style="background: #f0fdf4; color: #166534; padding: 14px 20px; border-radius: 12px; font-weight: 500; display: flex; flex-direction: column; gap: 10px; border: 1px solid #bbf7d0; margin-bottom: 8px; font-size: 13px;">
                    <div style="display: flex; align-items: center; gap: 8px; font-weight: 600;">
                        <i class="ph ph-check-circle" style="font-size: 20px;"></i>
                        Preview Data Berhasil Diimpor (Total: {{ count($summary['imported_data']) }})
                    </div>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 12px; background: white; border-radius: 8px; overflow: hidden; border: 1px solid #dcfce7;">
                            <thead>
                                <tr style="background: #dcfce7; padding: 0;">
                                    <th style="padding: 8px 12px; text-align: left; border-bottom: 1px solid #bbf7d0; background: #dcfce7;">Nama</th>
                                    <th style="padding: 8px 12px; text-align: left; border-bottom: 1px solid #bbf7d0; background: #dcfce7;">Email</th>
                                    <th style="padding: 8px 12px; text-align: left; border-bottom: 1px solid #bbf7d0; background: #dcfce7;">Telepon</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(array_slice($summary['imported_data'], 0, 5) as $data)
                                    <tr>
                                        <td style="padding: 8px 12px; border-bottom: 1px solid #f0fdf4;">{{ $data['name'] }}</td>
                                        <td style="padding: 8px 12px; border-bottom: 1px solid #f0fdf4;">{{ $data['email'] }}</td>
                                        <td style="padding: 8px 12px; border-bottom: 1px solid #f0fdf4;">{{ $data['phone'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(count($summary['imported_data']) > 5)
                        <div style="font-size: 12px; color: #15803d; margin-top: 4px;">
                            ...dan {{ count($summary['imported_data']) - 5 }} data lainnya berhasil diimpor.
                        </div>
                    @endif
                </div>
            @endif

            @if($summary['skipped'] > 0)
                <div style="background: #fffaf0; color: #c05621; padding: 14px 20px; border-radius: 12px; font-weight: 500; display: flex; flex-direction: column; gap: 10px; border: 1px solid #feebc8; margin-bottom: 8px; font-size: 13px;">
                    <div style="display: flex; align-items: center; gap: 8px; font-weight: 600;">
                        <i class="ph ph-warning-circle" style="font-size: 20px;"></i>
                        Hasil Validasi: Terdapat {{ $summary['skipped'] }} data yang dilewati/gagal diimpor
                    </div>
                    <ul style="margin: 0; padding-left: 24px; list-style-type: disc;">
                        @foreach(array_slice($summary['details'], 0, 10) as $detail)
                            <li>{{ $detail }}</li>
                        @endforeach
                        @if(count($summary['details']) > 10)
                            <li>...dan {{ count($summary['details']) - 10 }} data lainnya.</li>
                        @endif
                    </ul>
                </div>
            @endif
        @endif

        @if($errors->any())
            <div style="background: #fed7d7; color: #c53030; padding: 14px 20px; border-radius: 12px; font-weight: 600; display: flex; align-items: flex-start; gap: 10px; border: 1px solid #feb2b2; margin-bottom: 8px;">
                <i class="ph ph-warning-circle" style="font-size: 22px; margin-top: 2px;"></i>
                <ul style="margin: 0; padding-left: 18px; list-style-type: disc;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="section">
            <h2 class="section-title">Manajemen Pengguna</h2>

            <!-- Tab Navigation (Petugas vs Masyarakat) -->
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #edf2f7; margin-bottom: 24px; padding-bottom: 0px;">
                <div class="tabs-container" style="border-bottom: none; margin-bottom: 0; padding-bottom: 0;">
                    <a href="{{ route('admin.users.index', ['role' => 'petugas', 'search' => request('search')]) }}" class="tab-btn {{ $activeRole === 'petugas' ? 'active' : '' }}">
                        <i class="ph ph-shield-star" style="font-size: 18px;"></i> Data Petugas
                    </a>
                    <a href="{{ route('admin.users.index', ['role' => 'masyarakat', 'search' => request('search')]) }}" class="tab-btn {{ $activeRole === 'masyarakat' ? 'active' : '' }}">
                        <i class="ph ph-users" style="font-size: 18px;"></i> Data Masyarakat
                    </a>
                </div>

                @if($activeRole === 'petugas')
                <div style="padding-bottom: 12px; margin-bottom: -2px;">
                    <button type="button" onclick="document.getElementById('importModal').style.display='flex'" style="background: var(--primary); color: white; border: none; border-radius: 8px; padding: 10px 16px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s;">
                        <i class="ph ph-upload-simple" style="font-size: 16px;"></i> Import Petugas (CSV)
                    </button>
                </div>
                @endif
            </div>
            
            <form class="filters" method="GET" action="{{ route('admin.users.index') }}">
                <input type="hidden" name="role" value="{{ $activeRole }}">
                <div class="filter-group search-box">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" name="search" class="filter-input" placeholder="Cari nama, email, atau no telepon..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="filter-submit-btn">Cari</button>
            </form>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAMA</th>
                            <th>EMAIL</th>
                            <th>NO TELEPON</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>#{{ $user->users_id }}</td>
                            <td style="font-weight: 600;">{{ $user->users_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <a href="{{ route('admin.users.edit', $user->users_id) }}" class="btn-link">[Edit]</a>
                                    @if($user->users_id !== Auth::id())
                                    <form action="{{ route('admin.users.destroy', $user->users_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengguna ini?');" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #e53e3e; cursor: pointer; font-size: 14px; padding: 0;">[Hapus]</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #a0aec0; padding: 24px;">Pengguna tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="pagination-container">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </main>

    <!-- Modal Import -->
    <div id="importModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); align-items: center; justify-content: center;">
        <div style="background-color: #fefefe; margin: auto; padding: 24px; border: 1px solid #888; width: 400px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); position: relative; top: -10%;">
            <h3 style="margin-top: 0; color: #0f66aa; font-size: 18px;">Import Data Petugas</h3>
            <p style="font-size: 13px; color: #718096; margin-bottom: 16px;">Unggah file CSV dengan format kolom:<br><strong style="color: #4a5568;">Nama, Email, No Telepon, Password</strong><br><small>* Baris pertama akan diabaikan (header).</small></p>
            <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" accept=".csv,.txt" required style="margin-bottom: 24px; font-size: 13px; width: 100%; border: 1px dashed #cbd5e0; padding: 16px; border-radius: 8px;">
                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" onclick="document.getElementById('importModal').style.display='none'" style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 16px; cursor: pointer; font-size: 13px; font-weight: 600; color: #4a5568;">Batal</button>
                    <button type="submit" style="background: var(--primary); color: white; border: none; border-radius: 8px; padding: 8px 16px; cursor: pointer; font-size: 13px; font-weight: 600;">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
