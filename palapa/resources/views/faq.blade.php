<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>Pusat Bantuan - Palapa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons & AlpineJS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        html { scroll-behavior: smooth; }

        :root {
            --bg: #f4f7fb;
            --surface: #ffffff;
            --text: #1e293b;
            --muted: #64748b;
            --primary: #1f7ac6;
            --primary-light: #eff6ff;
            --primary-dark: #1267ad;
            --line: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(15, 23, 42, 0.1), 0 2px 4px -2px rgba(15, 23, 42, 0.05);
            --shadow-lg: 0 10px 15px -3px rgba(15, 23, 42, 0.1), 0 4px 6px -4px rgba(15, 23, 42, 0.05);
            --radius-xl: 20px;
            --radius-lg: 16px;
            --radius-md: 12px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .shell {
            display: flex;
            gap: 24px;
            padding: 16px;
            min-height: 100vh;
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            border-radius: var(--radius-xl);
            background: transparent;
            overflow: hidden;
            position: relative;
        }

        /* Header / Hero Section for FAQ */
        .faq-hero {
            background: linear-gradient(135deg, #1f7ac6 0%, #0d47a1 100%);
            border-radius: var(--radius-xl);
            padding: 60px 40px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            margin-bottom: -40px;
            z-index: 1;
        }

        .faq-hero::before {
            content: '';
            position: absolute;
            top: -50px;
            left: -50px;
            width: 250px;
            height: 250px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            filter: blur(40px);
        }

        .faq-hero::after {
            content: '';
            position: absolute;
            bottom: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            filter: blur(50px);
        }

        .faq-hero h1 {
            font-size: 38px;
            font-weight: 800;
            margin: 0 0 12px 0;
            position: relative;
            z-index: 2;
            letter-spacing: -0.5px;
        }

        .faq-hero p {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.85);
            max-width: 600px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
            line-height: 1.6;
        }

        /* FAQ Container */
        .faq-container {
            max-width: 850px;
            margin: 0 auto;
            width: 100%;
            position: relative;
            z-index: 2;
            padding: 0 20px 60px;
        }

        /* FAQ Items */
        .faq-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .faq-item {
            background: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--line);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .faq-item:hover {
            box-shadow: var(--shadow-md);
            border-color: #cbd5e1;
            transform: translateY(-2px);
        }

        .faq-item.active {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .faq-btn {
            width: 100%;
            padding: 24px;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            color: var(--text);
            gap: 16px;
        }

        .faq-btn .question-wrapper {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .faq-icon-bg {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .faq-item.active .faq-icon-bg {
            background: var(--primary);
            color: white;
        }

        .faq-btn span {
            font-weight: 700;
            font-size: 16px;
            line-height: 1.4;
        }

        .faq-toggle-icon {
            color: var(--muted);
            font-size: 20px;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), color 0.3s ease;
            flex-shrink: 0;
            background: #f1f5f9;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .faq-item.active .faq-toggle-icon {
            transform: rotate(180deg);
            background: var(--primary-light);
            color: var(--primary);
        }

        .faq-answer {
            padding: 0 24px 24px 84px; /* Align text with question, skipping icon */
            color: #475569;
            font-size: 15px;
            line-height: 1.7;
        }

        .faq-answer strong {
            color: var(--text);
            font-weight: 600;
        }

        /* Support Block */
        .support-block {
            margin-top: 40px;
            background: white;
            border-radius: var(--radius-lg);
            padding: 30px;
            text-align: center;
            border: 1px dashed var(--line);
            box-shadow: var(--shadow-sm);
        }

        .support-block h3 {
            margin: 0 0 8px 0;
            font-size: 20px;
            color: var(--text);
            font-weight: 700;
        }

        .support-block p {
            color: var(--muted);
            margin: 0 0 20px 0;
            font-size: 14px;
        }

        .support-block .btn-contact {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 99px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(31, 122, 198, 0.25);
        }

        .support-block .btn-contact:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(31, 122, 198, 0.35);
        }

        @media (max-width: 768px) {
            .faq-hero {
                padding: 40px 20px;
            }
            .faq-hero h1 {
                font-size: 28px;
            }
            .faq-answer {
                padding: 0 20px 20px 20px;
            }
            .faq-btn .question-wrapper {
                gap: 12px;
            }
            .faq-icon-bg {
                display: none; /* Hide icons on small screens for more text space */
            }
            .faq-container {
                padding: 0 10px 40px;
            }
        }
    </style>
</head>
<body>
<div class="shell" x-data="{ sidebarOpen: true }">
    @include('components.sidebar')

    <main class="content">
        <!-- Hero Section -->
        <div class="faq-hero">
            <h1>Pusat Bantuan Palapa</h1>
            <p>Temukan panduan, informasi teknis, dan jawaban atas pertanyaan yang paling sering diajukan seputar platform kami.</p>
        </div>

        <!-- FAQ Content -->
        <div class="faq-container" x-data="{ activeFaq: 1 }">
            <div class="faq-list">
                
                <!-- FAQ Item 1 -->
                <div class="faq-item" :class="activeFaq === 1 ? 'active' : ''">
                    <button @click="activeFaq = activeFaq === 1 ? null : 1" class="faq-btn">
                        <div class="question-wrapper">
                            <div class="faq-icon-bg"><i class="ph-fill ph-file-plus"></i></div>
                            <span>Bagaimana prosedur membuat laporan baru yang benar?</span>
                        </div>
                        <div class="faq-toggle-icon"><i class="ph-bold ph-caret-down"></i></div>
                    </button>
                    <div x-show="activeFaq === 1" x-collapse>
                        <div class="faq-answer">
                            Masuk ke menu <strong>Buat Laporan</strong> yang ada di panel sebelah kiri. Pertama, tentukan lokasi kejadian karhutla dengan menggeser pin pada peta interaktif atau mengetikkan nama daerah. Kedua, unggah <strong>foto bukti nyata</strong> dari lokasi kejadian. Terakhir, berikan detail tambahan seperti tingkat keparahan api (jika diketahui) pada kolom deskripsi, lalu klik <strong>Kirim Laporan</strong>.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="faq-item" :class="activeFaq === 2 ? 'active' : ''">
                    <button @click="activeFaq = activeFaq === 2 ? null : 2" class="faq-btn">
                        <div class="question-wrapper">
                            <div class="faq-icon-bg"><i class="ph-fill ph-magnifying-glass"></i></div>
                            <span>Di mana saya bisa memantau perkembangan laporan saya?</span>
                        </div>
                        <div class="faq-toggle-icon"><i class="ph-bold ph-caret-down"></i></div>
                    </button>
                    <div x-show="activeFaq === 2" x-collapse>
                        <div class="faq-answer">
                            Seluruh riwayat laporan Anda tersimpan di menu <strong>Profil Saya</strong>. Pada tabel riwayat, Anda dapat melihat status terkini secara <em>real-time</em> (contoh: <strong>Pending</strong>, <strong>Valid</strong>, <strong>Diproses</strong>, <strong>Selesai</strong>). Klik tombol <strong>Riwayat</strong> di baris laporan untuk melihat garis waktu <em>(timeline)</em> dan catatan dari petugas lapangan.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="faq-item" :class="activeFaq === 3 ? 'active' : ''">
                    <button @click="activeFaq = activeFaq === 3 ? null : 3" class="faq-btn">
                        <div class="question-wrapper">
                            <div class="faq-icon-bg"><i class="ph-fill ph-shield-warning"></i></div>
                            <span>Mengapa laporan saya diberi status 'Ditolak'?</span>
                        </div>
                        <div class="faq-toggle-icon"><i class="ph-bold ph-caret-down"></i></div>
                    </button>
                    <div x-show="activeFaq === 3" x-collapse>
                        <div class="faq-answer">
                            Tim verifikator kami meninjau setiap laporan yang masuk demi mencegah penyalahgunaan sistem. Laporan biasanya ditolak jika: foto bukti tidak relevan/buram, lokasi tidak akurat, laporan ganda, atau terindikasi <em>hoax</em>. Alasan spesifik penolakan akan selalu dicantumkan oleh Admin dan dapat Anda baca di menu Profil.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="faq-item" :class="activeFaq === 4 ? 'active' : ''">
                    <button @click="activeFaq = activeFaq === 4 ? null : 4" class="faq-btn">
                        <div class="question-wrapper">
                            <div class="faq-icon-bg"><i class="ph-fill ph-pencil-simple"></i></div>
                            <span>Apakah saya bisa mengedit atau membatalkan laporan?</span>
                        </div>
                        <div class="faq-toggle-icon"><i class="ph-bold ph-caret-down"></i></div>
                    </button>
                    <div x-show="activeFaq === 4" x-collapse>
                        <div class="faq-answer">
                            Ya, selama laporan masih berstatus <strong>Pending</strong> (menunggu verifikasi) atau <strong>Diproses</strong> (belum diakhiri), Anda bisa memperbarui detailnya. Pergi ke menu Profil Saya, cari laporan yang dimaksud, dan klik tombol <strong>Edit</strong>. Namun, sistem saat ini tidak menyediakan fitur hapus permanen agar data historis pelaporan tetap utuh.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="faq-item" :class="activeFaq === 5 ? 'active' : ''">
                    <button @click="activeFaq = activeFaq === 5 ? null : 5" class="faq-btn">
                        <div class="question-wrapper">
                            <div class="faq-icon-bg"><i class="ph-fill ph-bell-ringing"></i></div>
                            <span>Bagaimana cara kerja sistem notifikasi?</span>
                        </div>
                        <div class="faq-toggle-icon"><i class="ph-bold ph-caret-down"></i></div>
                    </button>
                    <div x-show="activeFaq === 5" x-collapse>
                        <div class="faq-answer">
                            Setiap kali ada perubahan status pada laporan Anda (misalnya: dari Pending menjadi Valid, atau jika petugas mulai turun ke lapangan), Anda akan menerima pemberitahuan otomatis. Anda dapat mengecek semua pemberitahuan ini dengan mengklik <strong>ikon lonceng</strong> di pojok kanan atas sidebar.
                        </div>
                    </div>
                </div>

            </div>

            <!-- Support Section -->
            <div class="support-block">
                <h3>Masih bingung atau punya kendala lain?</h3>
                <p>Tim dukungan teknis kami siap membantu Anda menyelesaikan masalah penggunaan aplikasi.</p>
                <a href="mailto:support@palapa.id" class="btn-contact">
                    <i class="ph-fill ph-envelope-simple"></i>
                    Hubungi Tim Dukungan
                </a>
            </div>
            
        </div>
    </main>
</div>
</body>
</html>