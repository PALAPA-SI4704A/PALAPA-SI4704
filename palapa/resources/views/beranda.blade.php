<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Palapa</title>
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
            --muted: #9aa4b2;
            --primary: #1f7ac6;
            --primary-dark: #1267ad;
            --line: #e7ebf1;
            --shadow: 0 14px 30px rgba(22, 34, 55, 0.08);
            --radius-lg: 24px;
            --radius-md: 16px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at 20% 10%, #f9fbff 0%, var(--bg) 45%, #edf1f6 100%);
            color: var(--text);
            min-height: 100vh;
        }

        .shell {
            display: flex;
            gap: 28px;
            padding: 18px;
            min-height: 100vh;
        }

        .content {
            flex: 1;
            padding: 8px 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .content-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .reports-button {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            background: var(--primary);
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 10px 18px rgba(31, 122, 198, 0.25);
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .reports-button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        h1 {
            font-size: 36px;
            margin: 0;
            color: #116db5;
            letter-spacing: 0.2px;
            font-weight: 800;
        }

        h2 {
            font-size: 35px;
            margin: 12px 0 18px;
            color: #1268ae;
            font-weight: 800;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 34px;
        }

        .card {
            background: var(--primary);
            color: #fff;
            border-radius: 0;
            box-shadow: 0 12px 22px rgba(31, 122, 198, 0.25);
            padding: 24px 16px;
            min-height: 192px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 30px rgba(31, 122, 198, 0.3);
        }

        .card .emoji {
            font-size: 74px;
            line-height: 1;
            margin-bottom: 8px;
        }

        .card .value {
            font-size: 36px;
            font-weight: 800;
            margin: 4px 0;
            line-height: 1.1;
        }

        .card .caption {
            margin: 0;
            font-size: 27px;
            line-height: 1.25;
            font-weight: 500;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(220px, 1fr));
            gap: 16px;
        }

        .news-card {
            background: var(--surface);
            border-radius: 8px;
            border: 1px solid #dbe1e9;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(16, 36, 63, 0.06);
        }

        .news-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
        }

        .news-card p {
            margin: 0;
            padding: 10px 10px 12px;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.35;
            color: #283241;
            text-transform: uppercase;
        }

        @media (max-width: 1200px) {
            h1 {
                font-size: 30px;
            }

            h2 {
                font-size: 28px;
            }

            .card .emoji {
                font-size: 56px;
            }

            .card .value {
                font-size: 30px;
            }

            .card .caption {
                font-size: 22px;
            }
        }

        @media (max-width: 980px) {
            .shell {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .news-grid {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 28px;
            }

            h2 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
<div class="shell" x-data="{ sidebarOpen: true }">
    @include('components.sidebar')

    <main class="content">
        <div class="content-head">
            <h1>Informasi</h1>
            <a href="{{ route('reports.index') }}" class="reports-button">Laporan Saya</a>
        </div>

        <section class="stats">
            <article class="card">
                <div class="emoji">🔥</div>
                <div class="value">32K+Ha</div>
                <p class="caption">Hutan &amp; Lahan<br>Terbakar 2026</p>
            </article>

            <article class="card">
                <div class="emoji">🧑‍🚒</div>
                <div class="value">568 Kasus</div>
                <p class="caption">Penanganan<br>Kebakaran 2026</p>
            </article>

            <article class="card">
                <div class="emoji">🎯</div>
                <div class="value">29 Provinsi</div>
                <p class="caption">Lokasi<br>Kebakaran 2026</p>
            </article>
        </section>

        <h2>Berita Terkini</h2>

        <section class="news-grid">
            <article class="news-card">
                <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=1200&auto=format&fit=crop" alt="Rapat penyusunan standar penghitungan luas karhutla">
                <p>Kemenhut menyusun standar baru penghitungan luas karhutla</p>
            </article>

            <article class="news-card">
                <img src="https://images.unsplash.com/photo-1574887427561-d3d92ec23a25?q=80&w=1200&auto=format&fit=crop" alt="Petugas pemadam menangani kebakaran lahan">
                <p>Jelang Idul Fitri, Kemenhut perkuat upaya pengendalian karhutla di Riau</p>
            </article>

            <article class="news-card">
                <img src="https://images.unsplash.com/photo-1475688621402-4257c812d6db?q=80&w=1200&auto=format&fit=crop" alt="Asap kebakaran di area hutan">
                <p>Kemenhut upayakan penanganan cepat karhutla di Kalbar</p>
            </article>
        </section>
    </main>
</div>
</body>
</html>
