<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>Palapa - Aplikasi Pelaporan Kebakaran Hutan dan Lahan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .hero-pattern {
            background-image: radial-gradient(circle at top right, rgba(31, 122, 198, 0.1) 0%, transparent 40%),
                              radial-gradient(circle at bottom left, rgba(31, 122, 198, 0.15) 0%, transparent 40%);
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-[#f3f5f8] text-[#2a2e38] antialiased overflow-x-hidden">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm py-4">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo-palapa.png') }}" alt="Palapa Logo" class="h-10">
            </div>
            <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-gray-700">
                <a href="#fitur" class="hover:text-[#1f7ac6] transition">Fitur</a>
                <a href="#tentang" class="hover:text-[#1f7ac6] transition">Tentang</a>
                <a href="#statistik" class="hover:text-[#1f7ac6] transition">Statistik</a>
                <a href="#faq" class="hover:text-[#1f7ac6] transition">FAQ</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="text-sm font-bold text-[#1f7ac6] hover:text-[#1267ad] transition">Masuk</a>
                <a href="{{ route('register') }}" class="text-sm font-bold bg-[#1f7ac6] hover:bg-[#1267ad] text-white px-6 py-2.5 rounded-full shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">Daftar</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 hero-pattern min-h-screen flex items-center">
        <div class="absolute inset-0 z-0">
            <!-- Decorative background elements -->
            <div class="absolute top-20 left-10 w-72 h-72 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 float-animation" style="animation-delay: 0s;"></div>
            <div class="absolute bottom-20 right-10 w-72 h-72 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 float-animation" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 grid lg:grid-cols-2 gap-12 items-center">
            <div class="text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-100 text-blue-800 text-sm font-semibold mb-6 shadow-sm">
                    <i class="ph-fill ph-shield-check text-lg"></i> Platform Pelaporan Resmi
                </div>
                <h1 class="text-5xl lg:text-7xl font-extrabold leading-tight tracking-tight text-[#116db5] mb-6">
                    Tanggap Cepat <br>
                    <span class="text-gray-900">Karhutla Bersama</span> <br>
                    Palapa.
                </h1>
                <p class="text-lg text-gray-600 mb-8 max-w-xl leading-relaxed">
                    Sistem informasi dan pelaporan kebakaran hutan dan lahan yang cepat, akurat, dan terintegrasi langsung dengan petugas lapangan di seluruh Indonesia.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('register') }}" class="inline-flex justify-center items-center gap-2 bg-[#1f7ac6] hover:bg-[#1267ad] text-white text-lg font-bold px-8 py-4 rounded-full shadow-xl hover:shadow-2xl transition transform hover:-translate-y-1">
                        Laporkan Sekarang
                        <i class="ph-bold ph-arrow-right"></i>
                    </a>
                    <a href="#fitur" class="inline-flex justify-center items-center gap-2 bg-white hover:bg-gray-50 text-gray-800 text-lg font-bold px-8 py-4 rounded-full shadow-md border border-gray-200 transition">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
            
            <div class="relative lg:h-[600px] flex items-center justify-center">
                <div class="relative w-full max-w-md float-animation">
                    <div class="absolute inset-0 bg-gradient-to-tr from-blue-600 to-blue-300 rounded-[2rem] transform rotate-3 scale-105 opacity-50 blur-lg"></div>
                    <img src="https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?q=80&w=1200&auto=format&fit=crop" alt="Kebakaran Hutan" class="relative z-10 rounded-[2rem] shadow-2xl object-cover h-[400px] lg:h-[500px] w-full border-4 border-white">
                    
                    <!-- Floating Badge -->
                    <div class="absolute -bottom-6 -left-6 z-20 bg-white p-4 rounded-2xl shadow-xl flex items-center gap-4 border border-gray-100 float-animation" style="animation-delay: 1s;">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                            <i class="ph-fill ph-fire text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase">Respon Cepat</p>
                            <p class="font-bold text-gray-900">< 15 Menit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-24 bg-gray-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="order-2 md:order-1 relative">
                    <div class="absolute inset-0 bg-blue-600 rounded-[2rem] transform -rotate-3 scale-105 opacity-10"></div>
                    <img src="https://images.unsplash.com/photo-1588681664899-f142ff2dc9b1?q=80&w=1200&auto=format&fit=crop" alt="Tim Penyelamat" class="relative z-10 rounded-[2rem] shadow-xl object-cover h-[400px] w-full border-4 border-white">
                </div>
                <div class="order-1 md:order-2">
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-[#116db5] mb-6">Tentang Palapa</h2>
                    <p class="text-lg text-gray-600 mb-4 leading-relaxed">
                        Palapa adalah platform informasi dan pelaporan terpadu yang menjembatani masyarakat dengan pihak berwenang dalam upaya mitigasi kebakaran hutan dan lahan (Karhutla) di seluruh wilayah Indonesia.
                    </p>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        Dibangun dengan dedikasi untuk pelestarian alam, sistem kami memastikan setiap titik api dapat dideteksi, dilaporkan, dan ditangani secepat mungkin sebelum meluas.
                    </p>
                    <div class="flex items-center gap-4 text-[#1f7ac6] font-bold">
                        <i class="ph-fill ph-check-circle text-2xl"></i>
                        <span>Terintegrasi Langsung dengan Petugas</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#116db5] mb-4">Kenapa Menggunakan Palapa?</h2>
                <p class="text-gray-600 text-lg">Solusi terpadu untuk mendeteksi, melaporkan, dan menangani kebakaran hutan dengan teknologi yang tepat guna.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-10">
                <div class="bg-gray-50 rounded-3xl p-8 hover:shadow-xl transition duration-300 border border-gray-100 group">
                    <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center text-[#1f7ac6] mb-6 group-hover:scale-110 transition">
                        <i class="ph-fill ph-bell-ringing text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Pelaporan Real-time</h3>
                    <p class="text-gray-600">Laporkan kejadian karhutla di sekitar Anda secara langsung. Sistem kami meneruskan laporan ke petugas terdekat seketika.</p>
                </div>
                
                <div class="bg-gray-50 rounded-3xl p-8 hover:shadow-xl transition duration-300 border border-gray-100 group">
                    <div class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center text-green-600 mb-6 group-hover:scale-110 transition">
                        <i class="ph-fill ph-map-pin-line text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Akurasi Lokasi</h3>
                    <p class="text-gray-600">Integrasi peta cerdas membantu petugas menemukan titik api dengan presisi tinggi untuk penanganan yang lebih cepat.</p>
                </div>

                <div class="bg-gray-50 rounded-3xl p-8 hover:shadow-xl transition duration-300 border border-gray-100 group">
                    <div class="w-16 h-16 rounded-2xl bg-orange-100 flex items-center justify-center text-orange-500 mb-6 group-hover:scale-110 transition">
                        <i class="ph-fill ph-chart-bar text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Pantau Status</h3>
                    <p class="text-gray-600">Lacak perkembangan laporan Anda secara transparan dari mulai verifikasi hingga penanganan selesai oleh tim lapangan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="statistik" class="py-24 bg-[#1f7ac6] relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 2px, transparent 2px); background-size: 30px 30px;"></div>
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="grid md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-blue-400">
                <div class="p-4">
                    <div class="text-4xl lg:text-5xl font-extrabold text-white mb-2">565+</div>
                    <div class="text-blue-100 font-semibold text-lg">Kasus Ditangani</div>
                </div>
                <div class="p-4">
                    <div class="text-4xl lg:text-5xl font-extrabold text-white mb-2">10K+</div>
                    <div class="text-blue-100 font-semibold text-lg">Warga Aktif</div>
                </div>
                <div class="p-4">
                    <div class="text-4xl lg:text-5xl font-extrabold text-white mb-2">29</div>
                    <div class="text-blue-100 font-semibold text-lg">Provinsi Terjangkau</div>
                </div>
                <div class="p-4">
                    <div class="text-4xl lg:text-5xl font-extrabold text-white mb-2">< 15m</div>
                    <div class="text-blue-100 font-semibold text-lg">Rata-rata Respon</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-24 bg-gray-50 border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-extrabold text-[#116db5] mb-4">Pertanyaan yang Sering Diajukan</h2>
                <p class="text-gray-600 text-lg">Temukan jawaban atas pertanyaan umum seputar platform Palapa di bawah ini.</p>
            </div>

            <div class="space-y-4" x-data="{ activeFaq: null }">
                <!-- FAQ Item 1 -->
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden transition-all duration-300" :class="activeFaq === 1 ? 'shadow-md ring-1 ring-blue-200' : 'hover:shadow-sm'">
                    <button @click="activeFaq = activeFaq === 1 ? null : 1" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none">
                        <span class="font-bold text-gray-900 text-lg">Apa itu aplikasi Palapa?</span>
                        <i class="ph-bold ph-caret-down text-blue-500 transition-transform duration-300 text-xl" :class="activeFaq === 1 ? 'transform rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeFaq === 1" x-collapse>
                        <div class="px-6 pb-6 text-gray-600 leading-relaxed border-t border-gray-100 pt-4 mt-2">
                            Palapa adalah platform sistem informasi pelaporan terpadu yang dibuat untuk memfasilitasi pelaporan kejadian kebakaran hutan dan lahan (Karhutla) oleh masyarakat secara real-time. Laporan ini kemudian diverifikasi dan diteruskan kepada petugas pemadam terdekat untuk penanganan cepat.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden transition-all duration-300" :class="activeFaq === 2 ? 'shadow-md ring-1 ring-blue-200' : 'hover:shadow-sm'">
                    <button @click="activeFaq = activeFaq === 2 ? null : 2" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none">
                        <span class="font-bold text-gray-900 text-lg">Apakah saya perlu membuat akun untuk melapor?</span>
                        <i class="ph-bold ph-caret-down text-blue-500 transition-transform duration-300 text-xl" :class="activeFaq === 2 ? 'transform rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeFaq === 2" x-collapse>
                        <div class="px-6 pb-6 text-gray-600 leading-relaxed border-t border-gray-100 pt-4 mt-2">
                            Ya, demi keamanan dan akurasi laporan serta untuk menghindari laporan palsu (hoax), pengguna diwajibkan untuk mendaftar akun terlebih dahulu menggunakan nomor telepon yang aktif (email bersifat opsional).
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden transition-all duration-300" :class="activeFaq === 3 ? 'shadow-md ring-1 ring-blue-200' : 'hover:shadow-sm'">
                    <button @click="activeFaq = activeFaq === 3 ? null : 3" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none">
                        <span class="font-bold text-gray-900 text-lg">Bagaimana cara melaporkan kebakaran?</span>
                        <i class="ph-bold ph-caret-down text-blue-500 transition-transform duration-300 text-xl" :class="activeFaq === 3 ? 'transform rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeFaq === 3" x-collapse>
                        <div class="px-6 pb-6 text-gray-600 leading-relaxed border-t border-gray-100 pt-4 mt-2">
                            Setelah login, Anda cukup menekan tombol "Buat Laporan Baru" di dashboard, pilih lokasi titik api di peta, lampirkan foto bukti kebakaran, dan berikan sedikit deskripsi terkait kondisi api. Laporan akan otomatis masuk antrean verifikasi Admin.
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden transition-all duration-300" :class="activeFaq === 4 ? 'shadow-md ring-1 ring-blue-200' : 'hover:shadow-sm'">
                    <button @click="activeFaq = activeFaq === 4 ? null : 4" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none">
                        <span class="font-bold text-gray-900 text-lg">Berapa lama waktu penanganan setelah laporan dibuat?</span>
                        <i class="ph-bold ph-caret-down text-blue-500 transition-transform duration-300 text-xl" :class="activeFaq === 4 ? 'transform rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeFaq === 4" x-collapse>
                        <div class="px-6 pb-6 text-gray-600 leading-relaxed border-t border-gray-100 pt-4 mt-2">
                            Admin Palapa beroperasi 24/7 dan rata-rata waktu verifikasi kurang dari 15 menit. Setelah laporan dinyatakan valid (Verified), petugas pemadam yang tersedia dan terdekat dengan lokasi akan langsung ditugaskan (In Progress).
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-white">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-[2.5rem] p-10 md:p-16 text-center shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-64 h-64 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 transform -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-64 h-64 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 transform translate-x-1/2 translate-y-1/2"></div>
                
                <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6 relative z-10">Mari Jaga Alam Kita.</h2>
                <p class="text-gray-300 text-lg md:text-xl mb-10 max-w-2xl mx-auto relative z-10">Satu laporan dari Anda bisa menyelamatkan ribuan hektar hutan. Bergabunglah dengan Palapa hari ini.</p>
                
                <a href="{{ route('register') }}" class="inline-flex justify-center items-center gap-2 bg-blue-500 hover:bg-blue-400 text-white text-lg font-bold px-10 py-4 rounded-full shadow-lg transition transform hover:-translate-y-1 relative z-10">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-200 py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-palapa.png') }}" alt="Palapa Logo" class="h-8 grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition">
            </div>
            <p class="text-gray-500 font-medium text-sm text-center md:text-left">
                &copy; 2026 Palapa. Hak Cipta Dilindungi. <br class="md:hidden"> Dibuat untuk kelestarian alam Indonesia.
            </p>
            <div class="flex gap-4 text-gray-400">
                <a href="#" class="hover:text-[#1f7ac6] transition"><i class="ph-fill ph-instagram-logo text-2xl"></i></a>
                <a href="#" class="hover:text-[#1f7ac6] transition"><i class="ph-fill ph-twitter-logo text-2xl"></i></a>
                <a href="#" class="hover:text-[#1f7ac6] transition"><i class="ph-fill ph-facebook-logo text-2xl"></i></a>
            </div>
        </div>
    </footer>

</body>
</html>