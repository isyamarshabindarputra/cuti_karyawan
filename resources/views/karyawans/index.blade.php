@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-blue-50 p-10">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-blue-700">Data Karyawan</h1>
        <a href="{{ route('karyawans.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow transition flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Karyawan
        </a>
    </div>

    <!-- Form pencarian -->
    <form action="{{ route('karyawans.index') }}" method="GET" class="mb-6 flex items-center space-x-2">
        <input type="text" name="search" placeholder="Cari Karyawan..."
               value="{{ request('search') }}"
               class="border border-blue-200 focus:ring-blue-400 focus:border-blue-400 rounded-lg px-4 py-2 w-64 shadow-sm">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            Cari
        </button>
    </form>

    <!-- Tabel data -->
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
        <table class="min-w-full border-collapse">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">NIP</th>
                    <th class="px-4 py-3 text-left">Nama Lengkap</th>
                    <th class="px-4 py-3 text-left">Jenis Kelamin</th>
                    <th class="px-4 py-3 text-left">Jabatan</th>
                    <th class="px-4 py-3 text-left">Bidang</th>
                    <th class="px-4 py-3 text-left">Sisa Cuti</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse($karyawans as $k)
                    <tr class="border-b hover:bg-blue-50 transition">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">{{ $k->nip }}</td>
                        <td class="px-4 py-3">{{ $k->name }}</td>
                        <td class="px-4 py-3">{{ $k->jenis_kelamin }}</td>
                        <td class="px-4 py-3">{{ $k->jabatan ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $k->bidang }}</td>
                        <td class="px-4 py-3">{{ $k->sisa_cuti }} hari</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col space-y-2">
                                <!-- Tombol hijau Pengajuan Cuti -->
                                <a href="{{ route('pengajuans.create', ['karyawan_id' => $k->id]) }}" 
                                   class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-center shadow-sm transition">
                                    <i class="fas fa-plane-departure mr-1"></i> Pengajuan Cuti
                                </a>

                                <!-- Tombol kuning Lihat Detail -->
                                <a href="{{ route('karyawans.show', $k->id) }}" 
                                   class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md text-center shadow-sm transition">
                                    <i class="fas fa-eye mr-1"></i> Lihat Detail
                                </a>

                                <!-- Baris edit dan hapus -->
                                <div class="flex justify-center space-x-2 pt-1">
                                    <a href="{{ route('karyawans.edit', $k->id) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm shadow">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('karyawans.destroy', $k->id) }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm shadow">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-500 py-6">Tidak ada data karyawan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $karyawans->links() }}
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-sm">
            {{ session('error') }}
        </div>
    @endif
</div>
@endsection
