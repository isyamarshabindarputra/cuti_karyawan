@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 max-w-3xl mx-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit Data Cuti</h2>

    <form action="{{ route('pengajuans.update', $pengajuans->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Jenis Cuti --}}
        <div class="mb-3">
            <label class="block font-medium text-gray-700 mb-1">Jenis Cuti</label>
            <select name="jenis_cuti" class="form-control w-full border-gray-300 rounded-md" required>
                <option value="Tahunan" {{ $pengajuans->jenis_cuti == 'Tahunan' ? 'selected' : '' }}>Tahunan</option>
                <option value="Sakit" {{ $pengajuans->jenis_cuti == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="Melahirkan" {{ $pengajuans->jenis_cuti == 'Melahirkan' ? 'selected' : '' }}>Melahirkan</option>
                <option value="Cuti Penting" {{ $pengajuans->jenis_cuti == 'Cuti Penting' ? 'selected' : '' }}>Cuti Penting</option>
                <option value="Cuti Besar" {{ $pengajuans->jenis_cuti == 'Cuti Besar' ? 'selected' : '' }}>Cuti Besar</option>
            </select>
        </div>

        {{-- Tanggal Mulai --}}
        <div class="mb-3">
            <label class="block font-medium text-gray-700 mb-1">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" class="form-control w-full border-gray-300 rounded-md"
                value="{{ \Carbon\Carbon::parse($pengajuans->tanggal_mulai)->format('Y-m-d') }}" required>
        </div>

        {{-- Tanggal Selesai --}}
        <div class="mb-3">
            <label class="block font-medium text-gray-700 mb-1">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" class="form-control w-full border-gray-300 rounded-md"
                value="{{ \Carbon\Carbon::parse($pengajuans->tanggal_selesai)->format('Y-m-d') }}" required>
        </div>

        {{-- Keterangan --}}
        <div class="mb-3">
            <label class="block font-medium text-gray-700 mb-1">Keterangan</label>
            <textarea name="keterangan" class="form-control w-full border-gray-300 rounded-md" rows="3">{{ $pengajuans->keterangan }}</textarea>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-between items-center mt-4">
            <button type="submit" class="btn btn-success bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                Simpan Perubahan
            </button>
            <a href="{{ route('pengajuans.index') }}" class="btn btn-secondary bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
