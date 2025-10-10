@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Data Cuti Karyawan</h2>
    <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Kembali
        </a>
    </div><br><br>

    <form action="{{ route('pengajuans.index') }}" method="GET">
        <input type="text" name="search" placeholder="Cari Pengajuan..." value="{{ request('search') }}">
        <button type="submit">Cari</button>
    </form>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama Karyawan</th>
                        <th>Jenis Cuti</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Jumlah Hari</th>
                        <th>Keterangan</th>
                        <th>Bidang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $index => $p)
                    <tr class="border-b-2 border-gray-400 hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $loop->iteration + ($pengajuans->currentPage() - 1) * $pengajuans->perPage() }}</td>
                        <td class="py-3 px-4">{{ $p->karyawan->name }}</td>
                        <td class="py-3 px-4">{{ $p->jenis_cuti }}</td>
                        <td class="py-3 px-4">{{ $p->tanggal_mulai }}</td>
                        <td class="py-3 px-4">{{ $p->tanggal_selesai }}</td>
                        <td class="py-3 px-4">{{ $p->jumlah_hari }} hari</td>
                        <td class="py-3 px-4">{{ $p->keterangan }}</td>
                        <td class="py-3 px-4">{{ $p->karyawan->bidang }}</td>
                        <td class="py-3 px-4">
                            

                            <div class="btn-group" role="group">
                                <a href="{{ route('pengajuans.edit', $p->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit">Edit</i>
                                </a>
                                
                                <form action="{{ route('pengajuans.destroy', $p->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data cuti ini?')"
                                            title="Hapus">
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
                        <td colspan="9" class="py-4 px-4 text-center">Tidak ada data pengajuan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
        <!-- ini buat notif data yang berhasil di input -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif
</div>
@endsection