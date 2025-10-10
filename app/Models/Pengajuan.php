<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;
    
    protected $table = 'pengajuans';

    protected $fillable = [
    //
    'karyawan_id',
    'jenis_cuti',
    'tanggal_mulai',
    'tanggal_selesai',
    'jumlah_hari',
    'keterangan',
    'bidang',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
