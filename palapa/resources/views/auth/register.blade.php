<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Palapa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    
    <div class="bg-white p-8 md:p-10 rounded-xl shadow-lg w-full max-w-md border border-gray-100">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Daftar Akun</h2>
            <p class="text-gray-500 mt-2 text-sm">Bergabunglah dengan platform Palapa</p>
        </div>
        
        <form action="{{ route('register') }}" method="POST">
            @csrf <div class="mb-5">
                <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" 
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors @error('name') border-red-500 bg-red-50 @enderror" required autofocus>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="contoh@email.com" 
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors @error('email') border-red-500 bg-red-50 @enderror" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Kata Sandi</label>
                <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" 
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors @error('password') border-red-500 bg-red-50 @enderror" required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-semibold mb-2">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi kata sandi" 
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-300 shadow-md">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center text-gray-600 text-sm mt-6">
            Sudah punya akun? <a href="#" class="text-blue-600 font-semibold hover:underline">Masuk di sini</a>
        </p>
    </div>

</body>
</html>