@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Karyawan</h2>
    <a href="{{ route('karyawans.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Kembali
    </a><br><br>
    <div class="card mb-3">
        <div class="card-header">Data Karyawan</div>
        <div class="card-body">
            <p><strong>Nama:</strong> {{ $karyawans->name }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Daftar Pengajuan Cuti</div>
        <div class="card-body">
            @if($karyawans->pengajuan->count())
                <table class="table">
                    <thead>
                        <tr>
                            <th>Jenis Cuti</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Jumlah Hari</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($karyawans->pengajuan as $pengajuan)
                        <tr>
                            <td>{{ $pengajuan->jenis_cuti }}</td>
                            <td>{{ $pengajuan->tanggal_mulai }}</td>
                            <td>{{ $pengajuan->tanggal_selesai }}</td>
                            <td>{{ $pengajuan->jumlah_hari }}</td>
                            <td>{{ $pengajuan->keterangan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Belum ada pengajuan cuti.</p>
            @endif
        </div>
    </div>
</div>
@endsection