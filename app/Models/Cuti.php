<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cuti extends Model
{
    use HasFactory;

    protected $table = 'cutis';

    protected $fillable = [
        'nama',
        'jumlah_hari_per_tahun',
        'keterangan',
        'is_active',
    ];

    /**
     * Get the karyawan that owns the Cuti
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}