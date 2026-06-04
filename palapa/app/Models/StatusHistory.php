<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    use HasFactory;

    protected $table = 'status_histories';

    protected $fillable = [
        'report_id',
        'status_awal',
        'status_baru',
        'catatan',
        'diubah_oleh',
        'tanggal_ubah'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id', 'report_id');
    }
}
