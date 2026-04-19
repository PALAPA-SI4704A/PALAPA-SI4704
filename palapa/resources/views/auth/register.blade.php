<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Palapa</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .input-h-50 { height: 50px; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-400 to-blue-900 min-h-screen flex items-center justify-center relative overflow-hidden p-6">

    <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-white/10 rounded-full"></div>
    <div class="absolute -top-20 -right-20 w-96 h-96 bg-white/10 rounded-full"></div>

    <div class="bg-white w-full max-w-5xl rounded-[30px] shadow-2xl relative z-10 overflow-hidden flex flex-col p-12 md:p-16">
        
        <div class="flex flex-col items-center justify-center mb-12">
            <img src="{{ asset('images/logo-palapa.png') }}" 
                 alt="Logo Palapa" 
                 class="w-[200px] md:w-[230px] h-auto object-contain drop-shadow-sm"
                 style="max-width: 230px;">
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-6">
            @csrf

            <div class="relative">
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    <input type="text" name="name" placeholder="Nama Lengkap Kamu" 
                        class="w-full pl-12 pr-4 input-h-50 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 outline-none transition text-gray-700 placeholder-gray-300">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="relative">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <input type="email" name="email" placeholder="namakamu@mail.com" 
                            class="w-full pl-12 pr-4 input-h-50 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 outline-none transition text-gray-700 placeholder-gray-300">
                    </div>
                </div>
                <div class="relative">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Nomor Telepon</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </span>
                        <input type="text" name="phone" placeholder="+62 000 000 000" 
                            class="w-full pl-12 pr-4 input-h-50 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 outline-none transition text-gray-700 placeholder-gray-300">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="relative">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password" name="password" placeholder="••••••••" 
                            class="w-full pl-12 pr-12 input-h-50 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 outline-none transition text-gray-700 placeholder-gray-300">
                    </div>
                </div>
                <div class="relative">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password" name="password_confirmation" placeholder="••••••••" 
                            class="w-full pl-12 pr-12 input-h-50 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 outline-none transition text-gray-700 placeholder-gray-300">
                    </div>
                </div>
            </div>

            <div class="pt-4 text-center">
                <p class="text-sm text-gray-500 mb-8">
                    Sudah memiliki akun? <a href="#" class="text-blue-600 font-bold underline hover:text-blue-800 transition">Masuk Sekarang.</a>
                </p>
                <button type="submit" class="w-full bg-gray-800 hover:bg-black text-white font-bold py-4 rounded-full shadow-xl transform transition active:scale-[0.98] tracking-widest">
                    DAFTAR
                </button>
            </div>
        </form>
    </div>

</body>
</html>