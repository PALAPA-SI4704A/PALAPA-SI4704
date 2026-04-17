<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model {
    protected $table = 'notifikasi';
    protected $primaryKey = 'notifikasi_id';
    public $timestamps = false;
    protected $fillable = ['user_id', 'pesan', 'is_read', 'created_at'];

    public function user() { return $this->belongsTo(User::class, 'user_id', 'users_id'); }
}