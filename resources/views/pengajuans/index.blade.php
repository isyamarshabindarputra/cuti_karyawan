@extends('layouts.app')

@section('title', 'Data Pengajuan')

@section('content')
<div class="min-h-screen bg-blue-50 p-10">

    <!-- Judul halaman -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-blue-700">Data Cuti Karyawan</h1>
    </div>

    <!-- Pencarian -->
    <form action="{{ route('pengajuans.index') }}" method="GET" class="mb-6 flex items-center space-x-2">
        <input type="text" name="search" placeholder="Cari Pengajuan..."
               value="{{ request('search') }}"
               class="border border-blue-200 focus:ring-blue-400 focus:border-blue-400 rounded-lg px-4 py-2 w-64">
        <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
            Cari
        </button>
    </form>

    <!-- Notif sukses -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- ini buat notif data yang gagal di input -->
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
        @endif

    <!-- Tabel data -->
    <div class="bg-white shadow-md rounded-2xl overflow-hidden">
        <table class="min-w-full border-collapse">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama Karyawan</th>
                    <th class="px-4 py-3 text-left">Jenis Cuti</th>
                    <th class="px-4 py-3 text-left">Tanggal Mulai</th>
                    <th class="px-4 py-3 text-left">Tanggal Selesai</th>
                    <th class="px-4 py-3 text-left">Jumlah Hari</th>
                    <th class="px-4 py-3 text-left">Keterangan</th>
                    <th class="px-4 py-3 text-left">Bidang</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuans as $p)
                    <tr class="border-b hover:bg-blue-50">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">{{ $p->karyawan->name }}</td>
                        <td class="px-4 py-3">{{ $p->jenis_cuti }}</td>
                        <td class="px-4 py-3">{{ $p->tanggal_mulai }}</td>
                        <td class="px-4 py-3">{{ $p->tanggal_selesai }}</td>
                        <td class="px-4 py-3">{{ $p->jumlah_hari }} hari</td>
                        <td class="px-4 py-3">{{ $p->keterangan }}</td>
                        <td class="px-4 py-3">{{ $p->karyawan->bidang }}</td>
                        <td class="px-4 py-3 flex space-x-2">
                            <a href="{{ route('pengajuans.edit', $p->id) }}"
                               class="bg-blue-400 hover:bg-blue-500 text-white px-3 py-1 rounded-md text-sm">
                               Edit
                            </a>
                            <form action="{{ route('pengajuans.destroy', $p->id) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data cuti ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center text-gray-500">Tidak ada data pengajuan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
