<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Menampilkan daftar notifikasi untuk pengguna yang sedang login.
     * Ini juga mencakup PBI 16: User dapat membuka notifikasi.
     */
    public function index()
    {
        // Ambil ID user yang sedang login
        $userId = Auth::user()->users_id;

        // Ambil semua notifikasinya, urutkan dari yang paling baru
        $notifikasis = Notifikasi::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notifikasi.index', compact('notifikasis'));
    }

    /**
     * Fungsi agar user bisa menandai notifikasi sudah dibaca.
     */
    public function markAsRead($id)
    {
        $userId = Auth::user()->users_id;

        // Cari notifikasi, pastikan itu milik user yang sedang login
        $notifikasi = Notifikasi::where('notifikasi_id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Update status is_read menjadi 1 (true)
        $notifikasi->update(['is_read' => 1]);

        return redirect()->back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /**
     * Fungsi Helper (Statis) untuk mempermudah rekan tim
     * memicu pembuatan notifikasi dari controller mereka.
     * 
     * Contoh penggunaan di controller rekan Anda:
     * \App\Http\Controllers\NotifikasiController::createNotification($userId, 'Status laporan Anda berubah!');
     */
    public static function createNotification($userId, $pesan)
    {
        return Notifikasi::create([
            'user_id' => $userId,
            'pesan' => $pesan,
            'is_read' => 0 // 0 berarti belum dibaca
        ]);
    }
}