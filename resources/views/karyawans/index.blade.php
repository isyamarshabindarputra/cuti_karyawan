@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Data Karyawan</h2>
        <a href="{{ route('karyawans.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Karyawan
        </a>
        <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Kembali
        </a>
    </div><br><br>


    <!-- tampilan buat mencari data yg sesuai di panggil -->
    <form action="{{ route('karyawans.index') }}" method="GET">
        <input type="text" name="search" placeholder="Cari Karyawan..." value="{{ request('search') }}">
        <button type="submit">Cari</button>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="py-3 px-4 text-left">#</th>
                    <th class="py-3 px-4 text-left">NIP</th>
                    <th class="py-3 px-4 text-left">Nama Lengkap</th>
                    <th class="py-3 px-4 text-left">Jenis Kelamin</th>
                    <th class="py-3 px-4 text-left">Jabatan</th>
                    <th class="py-3 px-4 text-left">Bidang</th>
                    <th class="py-3 px-4 text-left">Sisa Cuti</th>
                    <th class="py-3 px-4 text-left">Actions</th>
                </tr>
            </thead>
    </div>
    <tbody>
    @forelse($karyawans as $index => $k)
        <tr class="border-b-2 border-gray-400 hover:bg-gray-50">
            <td>{{ $loop->iteration + ($karyawans->currentPage() - 1) * $karyawans->perPage() }}</td>
            <td class="py-3 px-4">{{ $k->nip }}</td>
            <td class="py-3 px-4">{{ $k->name }}</td>
            <td class="py-3 px-4">{{ $k->jenis_kelamin }}</td>
            <td class="py-3 px-4">{{ $k->jabatan ?? '-' }}</td>
            <td class="py-3 px-4">{{ $k->bidang }}</td>
            <td class="py-3 px-4">{{ $k->sisa_cuti }} hari</td>
            <td class="py-3 px-4">
            <a href="{{ route('pengajuans.create', ['karyawan_id' => $k->id]) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>Pengajuan Cuti
            </a>

            <a href="{{ route('karyawans.show', $k->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                <i class="fas fa-eye">Lihat Details</i>
            </a>
            </td>
            <td class="py-3 px-4">
                <div class="flex justify-center space-x-2">
                    <a href="{{ route('karyawans.edit', $k->id) }}" class="text-green-500 hover:text-green-700">
                        <i class="fas fa-edit">Edit</i>
                    </a>
                <form action="{{ route('karyawans.destroy', $k->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash">Hapus</i>
                    </button>
                </form>
                </div>
            </td>
        </tr>

    <tr>
        <td colspan="9"><hr class="border-t-2 border-gray-300 my-2"></td>
    </tr>

    @empty
    <tr>
        <td colspan="9" class="py-4 px-4 text-center">Tidak ada data karyawan.</td>
    </tr>

    @endforelse
    </tbody>

        </table>
        {{ $karyawans->links() }}

        <!-- ini buat notif data yang berhasil di input -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif
        <!-- ini buat notif data yang gagal di input -->
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
        @endif

</div>
@endsection