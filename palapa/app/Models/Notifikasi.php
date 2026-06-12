<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    
    protected $table = 'notifikasi';

    
    protected $primaryKey = 'notifikasi_id';

    
    const UPDATED_AT = null;

    
    protected $fillable = [
        'user_id',
        'pesan',
        'is_read',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'users_id');
    }
}