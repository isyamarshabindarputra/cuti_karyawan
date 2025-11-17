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
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($karyawans->pengajuan as $pengajuan)
                    <tr class="hover:bg-blue-50">
                        <td class="py-3 px-4">{{ $pengajuan->jenis_cuti }}</td>
                        <td class="py-3 px-4">{{ $pengajuan->tanggal_mulai }}</td>
                        <td class="py-3 px-4">{{ $pengajuan->tanggal_selesai }}</td>
                        <td class="py-3 px-4">{{ $pengajuan->jumlah_hari }}</td>
                        <td class="py-3 px-4">
                            <div class="relative inline-block text-left">
                                <button type="button" onclick="toggleMenu(this)" class="inline-flex items-center p-2 rounded-full hover:bg-gray-100 focus:outline-none" aria-expanded="false" aria-haspopup="true">
                                    <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM18 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </button>

                                <div class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50" role="menu">
                                    <div class="py-1" role="none">
                                        <a href="{{ route('pengajuans.edit', $pengajuan->id) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100" role="menuitem">Edit</a>
                                        <form action="{{ route('pengajuans.destroy', $pengajuan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data cuti ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-slate-50">Hapus</button>
                                        </form>
                                        <button type="button" onclick="showKeterangan('{{ addslashes($pengajuan->keterangan) }}')" class="w-full text-left block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Keterangan</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Modal for Keterangan -->
        <div id="keteranganModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden z-50">
            <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-lg p-6 max-h-80 overflow-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-slate-800">Keterangan</h3>
                    <button onclick="closeKeterangan()" class="text-slate-500 hover:text-slate-800">✕</button>
                </div>
                <div id="keteranganContent" class="text-slate-700" style="white-space:pre-wrap; overflow-wrap:anywhere; word-break:break-word;"></div>
                <div class="mt-4 text-right">
                    <button onclick="closeKeterangan()" class="bg-blue-600 text-white px-4 py-2 rounded-md">Tutup</button>
                </div>
            </div>
        </div>

        <script>
            function closeAllMenus() {
                document.querySelectorAll('[role="menu"]').forEach(function(m){
                    m.classList.add('hidden');
                    m.style.position = '';
                    m.style.top = '';
                    m.style.left = '';
                });
            }

            function toggleMenu(button) {
                var menu = button.parentElement.querySelector('[role="menu"]');
                if (!menu) return;
                // close other menus
                document.querySelectorAll('[role="menu"]').forEach(function(m){ if (m !== menu) m.classList.add('hidden'); });

                // show menu so we can measure it
                menu.classList.remove('hidden');
                // position fixed to avoid parent overflow clipping
                menu.style.position = 'fixed';
                var rect = button.getBoundingClientRect();
                var top = rect.bottom + 6; // 6px gap
                var menuWidth = menu.offsetWidth || 160;
                var left = rect.right - menuWidth;
                if (left < 8) left = 8;
                menu.style.top = top + 'px';
                menu.style.left = left + 'px';
            }

            document.addEventListener('click', function(e) {
                document.querySelectorAll('[role="menu"]').forEach(function(menu){
                    if (!menu.classList.contains('hidden')) {
                        var parent = menu.parentElement;
                        if (!parent.contains(e.target) && !menu.contains(e.target)) {
                            menu.classList.add('hidden');
                            menu.style.position = '';
                            menu.style.top = '';
                            menu.style.left = '';
                        }
                    }
                });
            });

            // Close menus on scroll or resize so they don't linger in wrong positions
            window.addEventListener('scroll', function() { closeAllMenus(); }, true);
            window.addEventListener('resize', function() { closeAllMenus(); });

            function showKeterangan(text) {
                closeAllMenus();
                var modal = document.getElementById('keteranganModal');
                var content = document.getElementById('keteranganContent');
                content.textContent = text || '-';
                modal.classList.remove('hidden');
            }
            function closeKeterangan(){
                document.getElementById('keteranganModal').classList.add('hidden');
            }
        </script>
     @else
         <p class="text-gray-500 italic mt-4">Belum ada pengajuan cuti.</p>
     @endif
    </div>
</div>
@endsection
