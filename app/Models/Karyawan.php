<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawans';

    protected $fillable = [
        'user_id',
        'nip',
        'name',
        'jenis_kelamin',
        'jabatan',
        'bidang',
        'sisa_cuti',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class);
    }
}