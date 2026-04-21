<h1>Notifikasi Saya</h1>

<a href="{{ route('notifikasi.dummy') }}" style="padding: 10px; background-color: blue; color: white; text-decoration: none; border-radius: 5px;">
    Simulasikan Perubahan Status (Buat Dummy)
</a>

<br><br>

@if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
@endif

<ul>
    @forelse($notifikasis as $notif)
        <li style="{{ $notif->is_read == 0 ? 'font-weight: bold;' : '' }}">
            {{ $notif->pesan }} <br>
            <small style="color: gray;">{{ $notif->created_at }}</small>
        </li>
    @empty
        <li>Anda belum memiliki notifikasi.</li>
    @endforelse
</ul>

#hah#