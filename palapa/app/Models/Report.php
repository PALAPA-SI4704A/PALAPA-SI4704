<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Report extends Model {
    protected $primaryKey = 'report_id';
    protected $fillable = ['admin_id', 'user_id', 'title', 'description', 'photo', 'latitude', 'longitude', 'status'];

    public function pelapor() { return $this->belongsTo(User::class, 'user_id', 'users_id'); }
    public function admin() { return $this->belongsTo(User::class, 'admin_id', 'users_id'); }
    public function penugasans() { return $this->hasMany(Penugasan::class, 'report_id', 'report_id'); }
}