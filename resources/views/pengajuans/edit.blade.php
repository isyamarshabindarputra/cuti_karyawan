@extends('layouts.app')

@section('title', 'Tambah Pengajuan Cuti')

@section('content')

<div class="bg-white rounded-lg shadow-md p-6 max-w-3xl mx-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit Data Cuti</h2>

    <form action="{{ route('pengajuans.update', $pengajuans->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Pilih Karyawan -->
        <div class="mb-3">
            <label class="block font-medium text-gray-700 mb-1">Karyawan</label>
            <select name="karyawan_id" class="form-control w-full border-gray-300 rounded-md" required>
                @foreach($karyawans as $k)
                    <option value="{{ $k->id }}" {{ $k->id == $pengajuans->karyawan_id ? 'selected' : '' }}>{{ $k->name }} - {{ $k->nip ?? '' }}</option>
                @endforeach
            </select>
        </div>

        <!-- {{-- Jenis Cuti --}} -->
            <div>
                <label class="block text-gray-700 mb-2 font-medium" for="jenis_cuti">Jenis Cuti</label>
                <select name="jenis_cuti" id="jenis_cuti"
                            class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        <option value="">-- Pilih Jenis Cuti --</option>
                        <option value="Tahunan" {{ $pengajuans->jenis_cuti == 'Tahunan' ? 'selected' : '' }}>Tahunan</option>
                        <option value="Sakit" {{ $pengajuans->jenis_cuti == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Melahirkan" {{ $pengajuans->jenis_cuti == 'Melahirkan' ? 'selected' : '' }}>Melahirkan</option>
                        <option value="Penting" {{ $pengajuans->jenis_cuti == 'Penting' ? 'selected' : '' }}>Penting</option>
                    </select>
                @error('jenis_cuti') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

        <!-- {{-- Tanggal Mulai --}} -->
            <div>
                <label for="tanggal_mulai" class="block text-gray-700 mb-2 font-medium">Tanggal Mulai</label>
                  <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                       class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                      value="{{ old('tanggal_mulai', \Carbon\Carbon::parse($pengajuans->tanggal_mulai)->format('Y-m-d')) }}" required>
                @error('tanggal_mulai') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            
        <!-- {{-- Tanggal Selesai --}} -->
            <div>
                <label for="tanggal_selesai" class="block text-gray-700 mb-2 font-medium">Tanggal Selesai</label>
                  <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                       class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                      value="{{ old('tanggal_selesai', \Carbon\Carbon::parse($pengajuans->tanggal_selesai)->format('Y-m-d')) }}" required>
                @error('tanggal_selesai') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

        <!-- {{-- Keterangan --}} -->
        <div class="mb-3">
            <label class="block font-medium text-gray-700 mb-1">Keterangan</label>
            <textarea name="keterangan" class="form-control w-full border-gray-300 rounded-md" rows="3">{{ old('keterangan', $pengajuans->keterangan) }}</textarea>
        </div>

        <!-- {{-- Tombol Aksi --}} -->
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
