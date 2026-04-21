<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan - Palapa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f3f5f8;
            --surface: #ffffff;
            --text: #2a2e38;
            --muted: #8a94a5;
            --line: #e5eaf1;
            --primary: #1f76c2;
            --primary-dark: #165f9e;
            --danger: #df4b4b;
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

        .sidebar {
            width: 250px;
            flex: 0 0 250px;
            background: var(--surface);
            border-radius: 24px;
            box-shadow: var(--shadow);
            padding: 18px 14px;
            display: flex;
            flex-direction: column;
        }

        .brand {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding: 0 6px;
        }

        .brand img {
            width: 100px;
            height: auto;
            display: block;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8fafd;
            border-radius: 12px;
            padding: 9px;
            margin-bottom: 14px;
        }

        .profile img {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            object-fit: cover;
        }

        .profile p {
            margin: 0;
            line-height: 1.3;
        }

        .profile .name {
            font-size: 12px;
            font-weight: 700;
        }

        .profile .email {
            font-size: 11px;
            color: var(--muted);
            max-width: 160px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .menu,
        .menu-footer {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .menu a,
        .menu-footer a,
        .menu-footer button {
            text-decoration: none;
            border: 0;
            background: transparent;
            color: #6c788d;
            font-family: inherit;
            font-size: 13px;
            text-align: left;
            padding: 10px 12px;
            border-radius: 10px;
            cursor: pointer;
            transition: all .2s ease;
            display: block;
            width: 100%;
        }

        .menu a:hover,
        .menu-footer a:hover,
        .menu-footer button:hover {
            background: #eef5ff;
            color: var(--primary-dark);
        }

        .menu .active {
            background: #e7f1ff;
            color: var(--primary-dark);
            font-weight: 600;
        }

        .menu-footer {
            margin-top: auto;
            padding-top: 14px;
            border-top: 1px solid var(--line);
        }

        .menu-footer button:hover {
            background: #fff0f0;
            color: var(--danger);
        }

        .content {
            flex: 1;
            max-width: calc(100vw - 306px);
        }

        .panel {
            background: var(--surface);
            border-radius: 24px;
            box-shadow: var(--shadow);
            padding: 20px;
        }

        .title-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
        }

        .back {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            background: #e6f1ff;
            color: var(--primary-dark);
            font-weight: 700;
        }

        h1 {
            margin: 0;
            color: #0f66aa;
            font-size: 34px;
            font-weight: 800;
        }

        .error-box {
            margin-bottom: 14px;
            border: 1px solid #f3c7c7;
            border-radius: 12px;
            background: #fff1f1;
            color: #bf3e3e;
            padding: 10px 14px;
            font-size: 13px;
        }

        .error-box ul {
            margin: 0;
            padding-left: 18px;
        }

        .field { margin-bottom: 16px; }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 700;
            color: #2f3a4a;
        }

        .req { color: #d85858; }

        input[type="text"],
        textarea {
            width: 100%;
            border: 1px solid #dfe6ef;
            border-radius: 8px;
            font-size: 14px;
            padding: 11px 13px;
            font-family: inherit;
            outline: none;
            background: #fff;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        input[type="text"]:focus,
        textarea:focus {
            border-color: #8ec0ec;
            box-shadow: 0 0 0 3px rgba(31, 118, 194, .12);
        }

        .coord-row {
            display: grid;
            gap: 10px;
            grid-template-columns: 1fr 1fr auto;
            align-items: stretch;
        }

        .geo-btn {
            border: 0;
            border-radius: 8px;
            background: #d86338;
            color: white;
            font-size: 18px;
            width: 52px;
            cursor: pointer;
        }

        .upload-box {
            border: 1px solid #dfe6ef;
            border-radius: 14px;
            min-height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            background: #fcfdff;
            padding: 18px;
        }

        .upload-box input[type="file"] {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .upload-icon {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            margin: 0 auto 12px;
            background: #44c6ba;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 28px;
            box-shadow: 0 8px 20px rgba(68, 198, 186, .3);
        }

        .upload-text {
            margin: 0;
            color: #6e7d93;
            font-size: 13px;
            line-height: 1.5;
        }

        .upload-text strong { color: #d08433; }

        .preview-name {
            margin-top: 8px;
            font-size: 12px;
            color: #546379;
        }

        .preview-note {
            margin-top: 8px;
            color: #1f8b54;
            font-size: 12px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .submit {
            width: 100%;
            border: 0;
            border-radius: 12px;
            background: var(--primary);
            color: white;
            font-size: 18px;
            font-weight: 700;
            padding: 13px;
            cursor: pointer;
            transition: background .2s ease;
        }

        .submit:hover { background: var(--primary-dark); }

        @media (max-width: 980px) {
            .layout {
                flex-direction: column;
            }

            .sidebar,
            .content {
                width: 100%;
                max-width: none;
            }

            .coord-row {
                grid-template-columns: 1fr;
            }

            .geo-btn {
                width: 100%;
                padding: 10px;
            }

            h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="brand">
            <img src="{{ asset('images/logo-palapa.png') }}" alt="Logo Palapa">
            <span>&lsaquo;</span>
        </div>

        <div class="profile">
            <img src="https://i.pravatar.cc/96?img=12" alt="Avatar">
            <div>
                <p class="name">{{ auth()->user()->users_name }}</p>
                <p class="email">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <nav class="menu">
            <a href="{{ route('beranda') }}">o Beranda</a>
            <a class="active" href="{{ route('reports.create') }}">o Buat Laporan</a>
        </nav>

        <div class="menu-footer">
            <a href="#">o FAQ</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">o Keluar</button>
            </form>
        </div>
    </aside>

    <main class="content">
        <div class="panel">
            <div class="title-wrap">
                <a class="back" href="{{ route('beranda') }}">&larr;</a>
                <h1>Buat Laporan</h1>
            </div>

            @if ($errors->any())
                <div class="error-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('reports.preview') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="photo_temp" value="{{ old('photo_temp', $prefill['photo_temp'] ?? '') }}">

                <div class="field">
                    <label for="title">Judul <span class="req">*</span></label>
                    <input id="title" name="title" type="text" required
                        value="{{ old('title', $prefill['title'] ?? '') }}"
                        placeholder="Contoh: Asap tebal di area hutan dekat jalan utama">
                </div>

                <div class="field">
                    <label>Lokasi <span class="req">*</span></label>
                    <div class="coord-row">
                        <input id="latitude" name="latitude" type="text" required
                            value="{{ old('latitude', $prefill['latitude'] ?? '') }}"
                            placeholder="Latitude, contoh -6.200000">
                        <input id="longitude" name="longitude" type="text" required
                            value="{{ old('longitude', $prefill['longitude'] ?? '') }}"
                            placeholder="Longitude, contoh 106.816666">
                        <button id="use-location" class="geo-btn" type="button" title="Gunakan lokasi saat ini">GPS</button>
                    </div>
                </div>

                <div class="field">
                    <label for="photo">Upload Foto Kejadian</label>
                    <label class="upload-box">
                        <input id="photo" name="photo" type="file" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                        <div>
                            <div class="upload-icon">UP</div>
                            <p class="upload-text">Seret foto atau <strong>Pilih foto</strong><br>Format JPEG/PNG, maksimal 5 MB.</p>
                            <p class="preview-name" id="preview-name"></p>
                            @if(old('photo_temp', $prefill['photo_temp'] ?? ''))
                                <p class="preview-note">Foto sebelumnya masih tersimpan untuk preview.</p>
                            @endif
                        </div>
                    </label>
                </div>

                <div class="field">
                    <label for="description">Deskripsi Kejadian <span class="req">*</span></label>
                    <textarea id="description" name="description" required
                        placeholder="Jelaskan kondisi kebakaran yang terlihat (misalnya luas area, asap, atau kondisi sekitar)">{{ old('description', $prefill['description'] ?? '') }}</textarea>
                </div>

                <button class="submit" type="submit">Lanjutkan Preview</button>
            </form>
        </div>
    </main>
</div>

<script>
    const photoInput = document.getElementById('photo');
    const previewName = document.getElementById('preview-name');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const useLocationButton = document.getElementById('use-location');

    if (photoInput) {
        photoInput.addEventListener('change', function (event) {
            const file = event.target.files && event.target.files[0];
            previewName.textContent = file ? 'File dipilih: ' + file.name : '';
        });
    }

    if (useLocationButton) {
        useLocationButton.addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('Browser tidak mendukung geolokasi. Isi latitude dan longitude manual.');
                return;
            }

            navigator.geolocation.getCurrentPosition(function (position) {
                latitudeInput.value = position.coords.latitude.toFixed(6);
                longitudeInput.value = position.coords.longitude.toFixed(6);
            }, function () {
                alert('Lokasi tidak bisa diambil. Pastikan izin lokasi diaktifkan.');
            });
        });
    }
</script>
</body>
</html>
