@extends('layouts.app')

@section('title', 'Tambah Pengajuan Cuti')

@section('content')
<div class="max-w-3xl mx-auto">
    <h2 class="text-3xl font-semibold text-blue-700 mb-8">Form Pengajuan Cuti untuk {{ $karyawans->name }}</h2>

    <form action="{{ route('pengajuans.store', ['karyawan_id' => $karyawans->id]) }}" method="POST">
        @csrf

        <input type="hidden" name="karyawan_id" value="{{ $karyawans->id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 mb-2 font-medium" for="jenis_cuti">Jenis Cuti</label>
                <select name="jenis_cuti" id="jenis_cuti"
                        class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Pilih Jenis Cuti --</option>
                    <option value="Tahunan">Tahunan</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Melahirkan">Melahirkan</option>
                    <option value="Penting">Penting</option>
                </select>
                @error('jenis_cuti') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="tanggal_mulai" class="block text-gray-700 mb-2 font-medium">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                       class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       value="{{ old('tanggal_mulai') }}" required>
                @error('tanggal_mulai') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="tanggal_selesai" class="block text-gray-700 mb-2 font-medium">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                       class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       value="{{ old('tanggal_selesai') }}" required>
                @error('tanggal_selesai') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-4">
            <label for="keterangan" class="block text-gray-700 mb-2 font-medium">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="4"
                      class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                      placeholder="Tuliskan keterangan cuti...">{{ old('keterangan') }}</textarea>
            @error('keterangan') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end pt-4">
            <a href="{{ route('karyawans.show', $karyawans->id) }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-5 py-2 rounded-xl mr-2">
                Batal
            </a>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-xl shadow">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
