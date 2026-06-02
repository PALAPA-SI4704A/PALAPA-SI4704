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
        }

        /* Simple styling to override default Laravel pagination style if needed */
        .pagination-container nav {
            display: flex;
            gap: 4px;
        }
        .pagination-container nav a, .pagination-container nav span {
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #4a5568;
            text-decoration: none;
            font-size: 13px;
        }
        .pagination-container nav .active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
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
                            <th>ROLE</th>
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
                                @php
                                    $roleClass = 'badge-masyarakat';
                                    if($user->role == 'admin') $roleClass = 'badge-admin';
                                    if($user->role == 'petugas') $roleClass = 'badge-petugas';
                                @endphp
                                <span class="badge {{ $roleClass }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->users_id) }}" class="btn-link">[Edit]</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #a0aec0; padding: 24px;">Pengguna tidak ditemukan.</td>
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
