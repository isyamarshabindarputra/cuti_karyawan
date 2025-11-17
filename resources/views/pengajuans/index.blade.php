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
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-xl overflow-hidden">
                <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Nama Karyawan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Jenis Cuti</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Tanggal Mulai</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Tanggal Selesai</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Jumlah Hari</th>
                    <!--<th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Keterangan</th>-->
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Bidang</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @forelse($pengajuans as $p)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-medium">{{ $p->karyawan->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $p->jenis_cuti }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $p->tanggal_mulai }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $p->tanggal_selesai }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $p->jumlah_hari }} hari</td>
                        <!--<td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $p->keterangan }}</td>-->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $p->karyawan->bidang }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                            <div class="relative inline-block text-left">
                                <button type="button" onclick="toggleMenu(this)" class="inline-flex items-center p-2 rounded-full hover:bg-slate-100 focus:outline-none" aria-expanded="false" aria-haspopup="true"> 
                                    <!-- Ellipsis -->
                                    <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM18 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </button>

                                <div class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50" role="menu">
                                    <div class="py-1" role="none">
                                        <a href="{{ route('pengajuans.edit', $p->id) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100" role="menuitem">Edit</a>
                                        <form action="{{ route('pengajuans.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data cuti ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-slate-50">Hapus</button>
                                        </form>
                                        <button type="button" onclick="showKeterangan('{{ addslashes($p->keterangan) }}')" class="w-full text-left block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Keterangan</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-6 text-center text-slate-500">Tidak ada data pengajuan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

<!-- Modal for Keterangan -->
<div id="keteranganModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-slate-800">Keterangan</h3>
            <button onclick="closeKeterangan()" class="text-slate-500 hover:text-slate-800">✕</button>
        </div>
        <div id="keteranganContent" class="text-slate-700 whitespace-pre-wrap"></div>
        <div class="mt-4 text-right">
            <button onclick="closeKeterangan()" class="bg-blue-600 text-white px-4 py-2 rounded-md">Tutup</button>
        </div>
    </div>
</div>

<script>
    // close all open menus and reset inline styles
    function closeAllMenus() {
        document.querySelectorAll('[role="menu"]').forEach(function(m){
            if (!m.classList.contains('hidden')) {
                m.classList.add('hidden');
            }
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

        // show menu and position fixed to avoid clipping
        menu.classList.remove('hidden');
        menu.style.position = 'fixed';

        var rect = button.getBoundingClientRect();
        var gap = 6; // px
        var top = rect.bottom + gap;
        var menuWidth = menu.offsetWidth || 160;
        var left = rect.right - menuWidth;
        if (left < 8) left = 8;

        menu.style.top = top + 'px';
        menu.style.left = left + 'px';
    }

    // click outside to close
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

    // close menus on scroll/resize (capture phase for scroll)
    window.addEventListener('scroll', function() { closeAllMenus(); }, true);
    window.addEventListener('resize', function() { closeAllMenus(); });

    function showKeterangan(text) {
        closeAllMenus();
        var modal = document.getElementById('keteranganModal');
        var content = document.getElementById('keteranganContent');
        if(content) content.textContent = text || '-';
        if(modal) modal.classList.remove('hidden');
    }
    function closeKeterangan(){
        var modal = document.getElementById('keteranganModal');
        if(modal) modal.classList.add('hidden');
    }
</script>
