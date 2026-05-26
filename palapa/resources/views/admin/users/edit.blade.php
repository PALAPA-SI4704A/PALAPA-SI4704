<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>Edit Pengguna - Admin Palapa</title>
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
            --muted: #8a94a5;
            --line: #e5eaf1;
            --primary: #1f76c2;
            --primary-dark: #165f9e;
            --shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
            --danger: #e53e3e;
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

        .content {
            flex: 1;
            max-width: calc(100vw - 306px);
            transition: max-width 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .main-panel {
            background: var(--surface);
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            border: 1px solid #e2e8f0;
            max-width: 680px;
        }

        .panel-header {
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .panel-title {
            color: #0f66aa;
            font-size: 24px;
            font-weight: 800;
            margin: 0;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #718096;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: color 0.2s;
        }

        .btn-back:hover {
            color: var(--primary);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            font-family: inherit;
            font-size: 14px;
            color: #2d3748;
            background: #f8fafc;
            outline: none;
            transition: border-color 0.2s, background-color 0.2s;
        }

        .form-input:focus {
            border-color: var(--primary);
            background: white;
        }

        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23718096'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px;
            padding-right: 40px;
        }

        .input-error {
            color: var(--danger);
            font-size: 12px;
            margin-top: 6px;
            font-weight: 500;
            display: block;
        }

        .alert-error {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            color: var(--danger);
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-submit {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: var(--primary-dark);
        }

        .password-note {
            font-size: 12px;
            color: #a0aec0;
            margin-top: 4px;
            display: block;
        }

        @media (max-width: 980px) {
            .layout { flex-direction: column; }
            .content { max-width: none !important; }
            .main-panel { max-width: none; }
        }
    </style>
</head>
<body>
<div class="layout" x-data="{ sidebarOpen: true }">
    @include('components.sidebar')

    <main class="content" :style="sidebarOpen ? 'max-width: calc(100vw - 306px);' : 'max-width: calc(100vw - 138px);'">
        
        <div class="main-panel">
            <div class="panel-header">
                <h2 class="panel-title">Edit Data Pengguna</h2>
                <a href="{{ route('admin.users.index') }}" class="btn-back">
                    <i class="ph ph-arrow-left"></i> Kembali
                </a>
            </div>

            <!-- Alert Error Validasi Global -->
            @if($errors->any())
                <div class="alert-error">
                    <i class="ph ph-warning-circle" style="font-size: 16px; margin-right: 6px; vertical-align: middle;"></i>
                    Mohon perbaiki kesalahan pengisian form di bawah ini.
                </div>
            @endif

            <form action="{{ route('admin.users.update', $user->users_id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Input Nama -->
                <div class="form-group">
                    <label for="users_name" class="form-label">Nama Lengkap</label>
                    <input type="text" id="users_name" name="users_name" class="form-input" value="{{ old('users_name', $user->users_name) }}" required>
                    @error('users_name')
                        <span class="input-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Input Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="input-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Input Nomor Telepon -->
                <div class="form-group">
                    <label for="phone" class="form-label">Nomor Telepon</label>
                    <input type="text" id="phone" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" required>
                    @error('phone')
                        <span class="input-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Input Role -->
                <div class="form-group">
                    <label for="role" class="form-label">Hak Akses / Peran</label>
                    <select id="role" name="role" class="form-input" required>
                        <option value="masyarakat" {{ old('role', $user->role) === 'masyarakat' ? 'selected' : '' }}>Masyarakat</option>
                        <option value="petugas" {{ old('role', $user->role) === 'petugas' ? 'selected' : '' }}>Petugas Lapangan</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('role')
                        <span class="input-error">{{ $message }}</span>
                    @enderror
                </div>



                <div style="margin-top: 32px;">
                    <button type="submit" class="btn-submit">
                        <i class="ph ph-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </main>
</div>
</body>
</html>
