<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model {
    use HasFactory;

    protected $primaryKey = 'report_id';
    protected $fillable = ['admin_id', 'user_id', 'title', 'description', 'photo', 'latitude', 'longitude', 'address', 'status', 'rejection_reason', 'fire_level', 'assigned_petugas_id', 'handling_note', 'bukti_foto'];

    public function pelapor() { return $this->belongsTo(User::class, 'user_id', 'users_id'); }
    public function admin() { return $this->belongsTo(User::class, 'admin_id', 'users_id'); }
    public function assignedPetugas() { return $this->belongsTo(User::class, 'assigned_petugas_id', 'users_id'); }
    public function penugasans() { return $this->hasMany(Penugasan::class, 'report_id', 'report_id'); }
    public function statusHistories() { return $this->hasMany(StatusHistory::class, 'report_id', 'report_id'); }

    protected static function booted()
    {
        static::created(function ($report) {
            $user = auth()->user() ?: $report->pelapor;
            $name = $user ? $user->users_name : 'Sistem';
            $roleLabel = $user ? match($user->role) {
                'masyarakat' => 'Pelapor',
                'petugas' => 'Admin Sistem',
                'admin' => 'Admin Sistem',
                default => ucfirst($user->role)
            } : 'Pelapor';

            $report->statusHistories()->create([
                'status_awal' => null,
                'status_baru' => $report->status ?: 'pending',
                'catatan' => 'Laporan berhasil dibuat oleh pelapor.',
                'diubah_oleh' => "{$name} ({$roleLabel})",
                'tanggal_ubah' => now(),
            ]);
        });
    }
}