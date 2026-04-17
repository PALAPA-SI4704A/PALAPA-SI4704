<?php
    namespace App\Models;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;

    class User extends Authenticatable {
        use Notifiable;
        protected $primaryKey = 'users_id';
        protected $fillable = ['users_name', 'email', 'password', 'role', 'phone'];
        
        public function reports() { return $this->hasMany(Report::class, 'user_id', 'users_id'); }
        public function penugasans() { return $this->hasMany(Penugasan::class, 'petugas_id', 'users_id'); }
        public function notifikasis() { return $this->hasMany(Notifikasi::class, 'user_id', 'users_id'); }
    }