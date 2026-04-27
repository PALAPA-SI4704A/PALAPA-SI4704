<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Laporan - Palapa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at 15% 10%, #f8fbff 0%, #f1f4f8 40%, #edf1f6 100%);
            color: #243142;
            min-height: 100vh;
        }

        * { box-sizing: border-box; }

        .wrap {
            max-width: 1160px;
            margin: 0 auto;
            padding: 16px;
        }

        .panel {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
            padding: 22px;
        }

        .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        h1 {
            margin: 0;
            color: #0f66aa;
            font-size: 32px;
            font-weight: 800;
        }

        .sub {
            margin: 3px 0 0;
            color: #7d8899;
            font-size: 14px;
        }

        .btn {
            text-decoration: none;
            display: inline-block;
            background: #1f76c2;
            color: #fff;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
        }

        .success {
            margin-bottom: 12px;
            border: 1px solid #bbe6cc;
            border-radius: 10px;
            background: #ebfff2;
            color: #1f7d47;
            font-size: 13px;
            padding: 10px 12px;
        }

        .table-wrap {
            border: 1px solid #dfe6ef;
            border-radius: 16px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 780px;
            font-size: 14px;
        }

        th,
        td {
            padding: 12px 14px;
            border-bottom: 1px solid #edf1f6;
            text-align: left;
        }

        th {
            background: #f7f9fc;
            color: #607089;
            font-size: 13px;
            font-weight: 700;
        }

        tr:last-child td {
            border-bottom: 0;
        }

        .badge {
            display: inline-block;
            border-radius: 999px;
            background: #fff3d5;
            color: #996d00;
            font-size: 12px;
            font-weight: 700;
            padding: 5px 10px;
            text-transform: lowercase;
        }

        .photo-link {
            color: #1767ab;
            text-decoration: none;
            font-weight: 600;
        }

        .photo-link:hover {
            text-decoration: underline;
        }

        .empty {
            text-align: center;
            color: #8090a7;
            padding: 30px 10px;
        }

        .filters {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .filter-btn {
            text-decoration: none;
            display: inline-block;
            background: #f1f4f8;
            color: #607089;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            transition: all 0.2s;
        }

        .filter-btn:hover {
            background: #e2e8f0;
        }

        .filter-btn.active {
            background: #1f76c2;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="wrap">
    <main class="panel">
        <div class="head">
            <div>
                <h1>Laporan Saya</h1>
                <p class="sub">Daftar laporan titik api yang sudah kamu kirim.</p>
            </div>
            <a href="{{ route('reports.create') }}" class="btn">+ Buat Laporan Baru</a>
        </div>

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <div class="filters">
            <a href="{{ route('reports.index', ['status' => 'semua']) }}" class="filter-btn {{ $currentStatus === 'semua' ? 'active' : '' }}">Semua</a>
            <a href="{{ route('reports.index', ['status' => 'pending']) }}" class="filter-btn {{ $currentStatus === 'pending' ? 'active' : '' }}">Pending</a>
            <a href="{{ route('reports.index', ['status' => 'diproses']) }}" class="filter-btn {{ $currentStatus === 'diproses' ? 'active' : '' }}">Diproses</a>
            <a href="{{ route('reports.index', ['status' => 'selesai']) }}" class="filter-btn {{ $currentStatus === 'selesai' ? 'active' : '' }}">Selesai</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Judul</th>
                    <th>Koordinat</th>
                    <th>Status</th>
                    <th>Foto</th>
                    <th>Dikirim</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($reports as $report)
                    <tr>
                        <td>{{ $report->title }}</td>
                        <td>{{ $report->latitude }}, {{ $report->longitude }}</td>
                        <td><span class="badge">{{ $report->status }}</span></td>
                        <td>
                            @if ($report->photo)
                                <a class="photo-link" href="{{ route('reports.photo', ['path' => $report->photo]) }}" target="_blank">Lihat foto</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $report->created_at?->format('d M Y H:i') }}</td>
                        <td>
                            <a class="photo-link" href="{{ route('reports.history', $report->report_id) }}">Riwayat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">Belum ada laporan.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>
