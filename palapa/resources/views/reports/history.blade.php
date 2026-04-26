<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Status Laporan - Palapa</title>
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
            max-width: 800px;
            margin: 0 auto;
            padding: 16px;
        }

        .panel {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
            padding: 22px 32px;
        }

        .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
            border-bottom: 1px solid #edf1f6;
            padding-bottom: 16px;
        }

        h1 {
            margin: 0;
            color: #0f66aa;
            font-size: 28px;
            font-weight: 800;
        }

        .sub {
            margin: 3px 0 0;
            color: #7d8899;
            font-size: 14px;
        }

        .btn-back {
            text-decoration: none;
            display: inline-block;
            background: #f1f4f8;
            color: #607089;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            transition: background 0.2s;
        }

        .btn-back:hover {
            background: #e2e8f0;
        }

        /* Timeline Styles */
        .timeline {
            position: relative;
            padding-left: 20px;
            list-style: none;
            margin: 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 28px;
            width: 2px;
            background: #edf1f6;
        }

        .timeline-item {
            position: relative;
            padding-left: 40px;
            margin-bottom: 30px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-marker {
            position: absolute;
            left: 3px;
            top: 4px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #1f76c2;
            border: 2px solid #fff;
            box-shadow: 0 0 0 4px #edf1f6;
            z-index: 1;
        }

        .timeline-time {
            font-size: 12px;
            color: #8090a7;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .timeline-content {
            background: #f8fbff;
            border: 1px solid #dfe6ef;
            border-radius: 12px;
            padding: 16px;
        }

        .timeline-status {
            font-size: 16px;
            font-weight: 700;
            color: #243142;
            margin: 0 0 8px 0;
        }

        .badge {
            display: inline-block;
            border-radius: 999px;
            background: #fff3d5;
            color: #996d00;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 8px;
            text-transform: lowercase;
            margin-left: 8px;
            vertical-align: middle;
        }

        .badge.pending { background: #fff3d5; color: #996d00; }
        .badge.diproses { background: #e0f2fe; color: #0369a1; }
        .badge.selesai { background: #dcfce7; color: #166534; }

        .timeline-note {
            font-size: 14px;
            color: #607089;
            margin: 0 0 12px 0;
            line-height: 1.5;
        }

        .timeline-author {
            font-size: 12px;
            color: #8090a7;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .timeline-author::before {
            content: '👤';
            font-size: 10px;
        }

    </style>
</head>
<body>
<div class="wrap">
    <main class="panel">
        <div class="head">
            <div>
                <h1>Riwayat Status</h1>
                <p class="sub">{{ $report->title }}</p>
            </div>
            <a href="{{ route('reports.index') }}" class="btn-back">← Kembali</a>
        </div>

        <ul class="timeline">
            @foreach ($statusHistories as $history)
                <li class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-time">
                        {{ \Carbon\Carbon::parse($history['tanggal_ubah'])->translatedFormat('d F Y, H:i') }}
                    </div>
                    <div class="timeline-content">
                        <h3 class="timeline-status">
                            Status berubah menjadi 
                            <span class="badge {{ strtolower($history['status_baru']) }}">
                                {{ $history['status_baru'] }}
                            </span>
                        </h3>
                        <p class="timeline-note">
                            {{ $history['catatan'] }}
                        </p>
                        <div class="timeline-author">
                            Oleh: {{ $history['diubah_oleh'] }}
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </main>
</div>
</body>
</html>
