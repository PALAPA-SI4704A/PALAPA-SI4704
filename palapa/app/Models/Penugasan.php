<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model {
    protected $table = 'penugasan';
    protected $primaryKey = 'penugasan_id';
    public $timestamps = false;
    protected $fillable = ['report_id', 'petugas_id', 'assigned_at', 'completed_at', 'bukti_photo'];

    public function report() { return $this->belongsTo(Report::class, 'report_id', 'report_id'); }
    public function petugas() { return $this->belongsTo(User::class, 'petugas_id', 'users_id'); }
}