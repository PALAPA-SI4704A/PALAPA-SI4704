<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>Notifikasi - PALAPA</title>
    <!-- Import icon phosphor yang digunakan di sidebar Anda -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { 
            margin: 0; 
            font-family: sans-serif; 
            background-color: #f3f4f6; 
            display: flex; 
            min-height: 100vh; 
        }
        .main-content { 
            flex: 1; 
            padding: 30px; 
            overflow-y: auto; 
        }
        .card { 
            background: white; 
            padding: 25px; 
            border-radius: 12px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.05); 
            max-width: 800px;
            margin: 0 auto;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    
    <!-- Memanggil komponen sidebar yang sudah kita edit tadi -->
    <x-sidebar />

    <!-- Konten Utama Notifikasi -->
    <div class="main-content">
        <div class="card">
            <h2 style="margin-top: 0; color: #1f2937;">Notifikasi Saya</h2>
            <hr style="margin-bottom: 20px; border: 0; border-top: 1px solid #e5e7eb;">
            
            <!-- Pesan Sukses jika tombol dibaca ditekan -->
            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Logika Daftar Notifikasi -->
            @if($notifikasis->isEmpty())
                <div style="text-align: center; padding: 40px 0; color: #6b7280;">
                    <i class="ph ph-bell-slash" style="font-size: 48px; margin-bottom: 10px;"></i>
                    <p>Anda belum memiliki notifikasi.</p>
                </div>
            @else
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach($notifikasis as $notif)
                        <li style="padding: 16px; border: 1px solid #e5e7eb; border-radius: 10px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center; {{ $notif->is_read == 0 ? 'background-color: #eff6ff; border-color: #bfdbfe;' : 'background-color: #ffffff;' }}">
                            
                            <div>
                                <p style="margin: 0 0 4px 0; font-size: 15px; color: #111827; font-weight: {{ $notif->is_read == 0 ? '700' : '400' }};">
                                    {{ $notif->pesan }}
                                </p>
                                <small style="color: #6b7280; font-size: 12px;">
                                    <i class="ph ph-clock"></i> {{ $notif->created_at->diffForHumans() }}
                                </small>
                            </div>

                            @if($notif->is_read == 0)
                                <form action="{{ route('notifikasi.read', $notif->notifikasi_id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="background-color: #3b82f6; color: white; border: none; padding: 8px 14px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500; transition: background 0.2s;">
                                        Tandai Dibaca
                                    </button>
                                </form>
                            @else
                                <span style="color: #9ca3af; font-size: 13px; display: flex; align-items: center; gap: 4px;">
                                    <i class="ph ph-check-circle"></i> Sudah dibaca
                                </span>
                            @endif
                            
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

</body>
</html>