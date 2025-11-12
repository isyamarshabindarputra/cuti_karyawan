<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Karyawan')</title>
      <!-- Tambahkan favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f4f8ff] text-gray-800">

<div class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside class="w-64 bg-white m-4 rounded-3xl shadow-lg p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-2xl font-bold text-blue-600 mb-8">Menu Utama</h2>
            <nav class="space-y-3">
                <a href="{{ route('dashboard') }}"
                   class="block py-2.5 px-4 rounded-xl font-medium transition
                          {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-blue-600 hover:bg-blue-100 hover:text-blue-800' }}">
                    Dashboard
                </a>
                <a href="{{ route('karyawans.index') }}"
                   class="block py-2.5 px-4 rounded-xl font-medium transition
                          {{ request()->routeIs('karyawans.*') ? 'bg-blue-600 text-white' : 'text-blue-600 hover:bg-blue-100 hover:text-blue-800' }}">
                    Manajemen Karyawan
                </a>
                <a href="{{ route('pengajuans.index') }}"
                   class="block py-2.5 px-4 rounded-xl font-medium transition
                          {{ request()->routeIs('pengajuans.*') ? 'bg-blue-600 text-white' : 'text-blue-600 hover:bg-blue-100 hover:text-blue-800' }}">
                    Data Pengajuan
                </a>
            </nav>
        </div>
        <div class="text-sm text-gray-400 mt-6">
            © {{ date('Y') }} Sistem Karyawan
        </div>
    </aside>

    {{-- Konten utama --}}
    <div class="flex-1 p-8">
        {{-- Header kanan atas --}}
        <div class="flex justify-end mb-6">
            <div class="flex items-center bg-white rounded-full shadow px-4 py-2 space-x-3">
                <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                    {{ strtoupper(substr(Auth::user()->name,0,2)) }}
                </div>
                <div class="text-right">
                    <p class="font-semibold text-blue-600">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-gray-500">Terdaftar {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('d M Y') }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="ml-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded-full">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- Konten halaman --}}
        <div class="bg-white rounded-3xl shadow p-8">
            @yield('content')
        </div>
    </div>
</div>

</body>
</html>
