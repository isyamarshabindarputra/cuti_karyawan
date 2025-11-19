@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 max-w-3xl mx-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit Data Karyawan</h2>

    <form action="{{ route('karyawans.update', $karyawans) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Baris 1: NIP dan Nama --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="nip" class="block text-gray-700 mb-2">NIP</label>
                <input type="text" name="nip" id="nip" 
                       value="{{ $karyawans->nip }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('nip') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>

            <div>
                <label for="name" class="block text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" 
                       value="{{ $karyawans->name }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('name') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>
        </div>

        {{-- Baris 2: Jenis Kelamin dan Jabatan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="jenis_kelamin" class="block text-gray-700 mb-2">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" {{ $karyawans->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ $karyawans->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>

            <div>
                <label for="jabatan" class="block text-gray-700 mb-2">Jabatan</label>
                <input type="text" name="jabatan" id="jabatan" 
                       value="{{ $karyawans->jabatan }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('jabatan') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>
        </div>

        {{-- Baris 3: Bidang --}}
        <div class="mb-6">
            <label for="bidang" class="block text-gray-700 mb-2">Bidang</label>
            <select name="bidang" id="bidang" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="Sekre" {{ $karyawans->bidang == 'Sekre' ? 'selected' : '' }}>Sekre</option>
                <option value="TIK" {{ $karyawans->bidang == 'TIK' ? 'selected' : '' }}>TIK</option>
                <option value="Stasan" {{ $karyawans->bidang == 'Stasan' ? 'selected' : '' }}>Stasan</option>
                <option value="PT" {{ $karyawans->bidang == 'PT' ? 'selected' : '' }}>PT</option>
            </select>
            @error('bidang') 
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end">
            <a href="{{ route('karyawans.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg mr-2">
               Batal
            </a>
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Simpan
            </button>
        </div>
    </form>
</div>
@endsection
