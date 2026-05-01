<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    // Pastikan nama tabel benar
    protected $table = 'notifikasi';

    // Sesuaikan primary key dengan migrasi
    protected $primaryKey = 'notifikasi_id';

    // Nonaktifkan kolom updated_at agar Laravel tidak mencarinya
    const UPDATED_AT = null;

    // Izinkan mass assignment untuk kolom-kolom ini
    protected $fillable = [
        'user_id',
        'pesan',
        'is_read',
    ];

    // Buat relasi ke tabel users (karena setiap notifikasi milik satu user)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'users_id');
    }
}