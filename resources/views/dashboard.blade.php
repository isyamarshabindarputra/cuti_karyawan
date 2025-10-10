<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Karyawan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Sistem Karyawan</h1>
            <div class="flex items-center space-x-4">
                <span>Halo, {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-blue-800 hover:bg-blue-900 text-white py-1 px-3 rounded">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Dashboard</h2>
            <p class="mb-4">Selamat datang di sistem manajemen karyawan.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-blue-100 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-2">Informasi Pengguna</h3>
                    <p><strong>Nama:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>Role:</strong> {{ Auth::user()->role }}</p>
                    <p><strong>Terdaftar sejak:</strong> {{ Auth::user()->created_at->format('d M Y') }}</p>
                </div>
                
            <div class="bg-green-100 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-2">Menu Utama</h3>
             <div class="space-y-2">
             <a href="{{ route('karyawans.index') }}" 
              class="block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow">
             ➤ Manajemen Karyawan
            </a>
            <a href="{{ route('pengajuans.index') }}" 
            class="block bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow">
            ➤ Data Pengajuan
            <a href="{{ route('documents.index') }}" 
            class="block bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg shadow">
           ➤ Ducument
               </a>
                </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>