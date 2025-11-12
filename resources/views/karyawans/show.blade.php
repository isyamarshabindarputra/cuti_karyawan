@extends('layouts.app')

@section('title', 'Detail Karyawan')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-semibold text-blue-700">Detail Karyawan</h2>
        <a href="{{ route('karyawans.index') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-xl shadow">
            ← Kembali
        </a>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-8 shadow-sm">
        <h3 class="text-xl font-semibold text-blue-700 mb-4">Data Karyawan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
            <p><strong>NIP:</strong> {{ $karyawans->nip }}</p>
            <p><strong>Nama:</strong> {{ $karyawans->name }}</p>
            <p><strong>Jenis Kelamin:</strong> {{ $karyawans->jenis_kelamin }}</p>
            <p><strong>Jabatan:</strong> {{ $karyawans->jabatan }}</p>
            <p><strong>Bidang:</strong> {{ $karyawans->bidang }}</p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-blue-700">Daftar Pengajuan Cuti</h3>
            <a href="{{ route('pengajuans.create', ['karyawan_id' => $karyawans->id]) }}"
   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-medium shadow">
    + Tambah Pengajuan
        </a>

        </div>

        @if($karyawans->pengajuan->count())
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-xl overflow-hidden">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">Jenis Cuti</th>
                        <th class="py-3 px-4 text-left">Tanggal Mulai</th>
                        <th class="py-3 px-4 text-left">Tanggal Selesai</th>
                        <th class="py-3 px-4 text-left">Jumlah Hari</th>
                        <th class="py-3 px-4 text-left">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($karyawans->pengajuan as $pengajuan)
                    <tr class="hover:bg-blue-50">
                        <td class="py-3 px-4">{{ $pengajuan->jenis_cuti }}</td>
                        <td class="py-3 px-4">{{ $pengajuan->tanggal_mulai }}</td>
                        <td class="py-3 px-4">{{ $pengajuan->tanggal_selesai }}</td>
                        <td class="py-3 px-4">{{ $pengajuan->jumlah_hari }}</td>
                        <td class="py-3 px-4">{{ $pengajuan->keterangan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-500 italic mt-4">Belum ada pengajuan cuti.</p>
        @endif
    </div>
</div>
@endsection
