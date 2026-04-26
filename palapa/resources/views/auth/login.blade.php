<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Palapa</title>
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

    <div class="bg-white w-full max-w-md rounded-[30px] shadow-2xl relative z-10 overflow-hidden flex flex-col p-10 md:p-12">
        
        <div class="flex flex-col items-center justify-center mb-10">
            <img src="{{ asset('images/logo-palapa.png') }}" 
                 alt="Logo Palapa" 
                 class="w-[180px] md:w-[200px] h-auto object-contain drop-shadow-sm"
                 style="max-width: 200px;">
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf

            <div class="relative">
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="namakamu@mail.com" required
                        class="w-full pl-12 pr-4 input-h-50 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 outline-none transition text-gray-700 placeholder-gray-300">
                </div>
            </div>

            <div class="relative">
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input type="password" name="password" placeholder="••••••••" required
                        class="w-full pl-12 pr-12 input-h-50 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 outline-none transition text-gray-700 placeholder-gray-300">
                </div>
            </div>

            <div class="flex items-center justify-between !mt-4">
                <label class="flex items-center text-sm text-gray-500 cursor-pointer hover:text-gray-700 transition">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 transition">
                    <span class="ml-2 font-medium">Ingat saya</span>
                </label>
                <a href="#" class="text-sm text-blue-600 font-medium hover:underline transition">Lupa sandi?</a>
            </div>

            <div class="pt-4 text-center">
                <p class="text-sm text-gray-500 mb-8">
                    Belum memiliki akun? <a href="{{ route('register') }}" class="text-blue-600 font-bold underline hover:text-blue-800 transition">Daftar Sekarang.</a>
                </p>
                <button type="submit" class="w-full bg-gray-800 hover:bg-black text-white font-bold py-4 rounded-full shadow-xl transform transition active:scale-[0.98] tracking-widest uppercase">
                    Masuk
                </button>
            </div>
        </form>
    </div>

</body>
</html>
