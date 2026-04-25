<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Laporan - Palapa</title>
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
            max-width: 1060px;
            margin: 0 auto;
            padding: 16px;
        }

        .panel {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
            padding: 22px;
        }

        h1 {
            margin: 0 0 4px;
            color: #0f66aa;
            font-size: 32px;
            font-weight: 800;
        }

        .subtitle {
            margin: 0 0 18px;
            color: #7d8899;
            font-size: 14px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .card {
            border: 1px solid #dfe6ef;
            border-radius: 16px;
            padding: 14px;
        }

        .card h2 {
            margin: 0 0 12px;
            font-size: 18px;
            color: #2d3a4c;
        }

        .item {
            margin-bottom: 10px;
        }

        .item strong {
            display: block;
            margin-bottom: 2px;
            font-size: 13px;
            color: #637188;
        }

        .item p {
            margin: 0;
            font-size: 14px;
            color: #253244;
            line-height: 1.5;
        }

        .badge {
            display: inline-block;
            border-radius: 999px;
            background: #fff3d5;
            color: #996d00;
            font-size: 12px;
            font-weight: 700;
            padding: 5px 10px;
        }

        .photo {
            width: 100%;
            height: 280px;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid #e3e8ef;
            display: block;
        }

        .photo-empty {
            height: 280px;
            border-radius: 12px;
            border: 1px solid #e3e8ef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7b8797;
            background: #f7fafc;
            font-size: 14px;
        }

        .actions {
            margin-top: 18px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn {
            border: 0;
            border-radius: 10px;
            padding: 11px 18px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-outline {
            border: 1px solid #d0d8e4;
            background: #fff;
            color: #334154;
        }

        .btn-primary {
            background: #1f76c2;
            color: #fff;
        }

        .btn-primary:hover { background: #175f9a; }

        @media (max-width: 900px) {
            .grid { grid-template-columns: 1fr; }
            .actions {
                flex-direction: column;
            }
            .actions form { width: 100%; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>
<div class="wrap">
    <main class="panel">
        <h1>Preview Laporan</h1>
        <p class="subtitle">Periksa data sebelum disimpan ke sistem.</p>

        <div class="grid">
            <section class="card">
                <h2>Detail Laporan</h2>
                <div class="item">
                    <strong>Judul</strong>
                    <p>{{ $data['title'] }}</p>
                </div>
                <div class="item">
                    <strong>Deskripsi</strong>
                    <p>{{ $data['description'] }}</p>
                </div>
                <div class="item">
                    <strong>Latitude</strong>
                    <p>{{ $data['latitude'] }}</p>
                </div>
                <div class="item">
                    <strong>Longitude</strong>
                    <p>{{ $data['longitude'] }}</p>
                </div>
                <div class="item">
                    <strong>Status Awal</strong>
                    <span class="badge">pending</span>
                </div>
            </section>

            <section class="card">
                <h2>Foto Kejadian</h2>
                @if (!empty($data['photo_temp']))
                    <img src="{{ route('reports.photo', ['path' => $data['photo_temp']]) }}" alt="Preview foto laporan" class="photo">
                @else
                    <div class="photo-empty">Tidak ada foto diunggah</div>
                @endif
            </section>
        </div>

        <div class="actions">
            <form action="{{ route('reports.create') }}" method="GET">
                <input type="hidden" name="title" value="{{ $data['title'] }}">
                <input type="hidden" name="description" value="{{ $data['description'] }}">
                <input type="hidden" name="latitude" value="{{ $data['latitude'] }}">
                <input type="hidden" name="longitude" value="{{ $data['longitude'] }}">
                <input type="hidden" name="photo_temp" value="{{ $data['photo_temp'] ?? '' }}">
                <button type="submit" class="btn btn-outline">Edit</button>
            </form>

            <form action="{{ route('reports.store') }}" method="POST">
                @csrf
                <input type="hidden" name="title" value="{{ $data['title'] }}">
                <input type="hidden" name="description" value="{{ $data['description'] }}">
                <input type="hidden" name="latitude" value="{{ $data['latitude'] }}">
                <input type="hidden" name="longitude" value="{{ $data['longitude'] }}">
                <input type="hidden" name="photo_temp" value="{{ $data['photo_temp'] ?? '' }}">
                <button type="submit" class="btn btn-primary">Confirm dan Simpan</button>
            </form>
        </div>
    </main>
</div>
</body>
</html>
