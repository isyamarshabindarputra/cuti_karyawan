<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DocumentController;
use App\Models\Cuti;
use App\Models\Karyawan;

// ini bagian dari link/url tampilan karyawan
Route::get('/karyawans',[KaryawanController::class, 
'index'])->middleware('auth')->name('karyawans.index');

Route::get('/karyawans/create',[KaryawanController::class, 
'create'])->name('karyawans.create');

Route::post('/karyawans',[KaryawanController::class, 
'store'])->name('karyawans.store');

Route::get('/karyawan/{karyawan_id}', [KaryawanController::class, 
'show'])->name('pengajuans.show');

Route::get('/karyawans/{karyawans}/edit',[KaryawanController::class, 
'edit'])->name('karyawans.edit');

Route::put('/karyawans/{karyawans}',[KaryawanController::class, 
'update'])->name('karyawans.update');

Route::delete('karyawans/{karyawans}',[KaryawanController::class, 
'destroy'])->name('karyawans.destroy');

// ini bagian dari link/url tampilan pengajuan
Route::get('/pengajuans', [PengajuanController::class, 
'index'])->middleware('auth')->name('pengajuans.index');

Route::get('/pengajuans/create/{karyawan_id}', [PengajuanController::class, 
'create'])->name('pengajuans.create');

Route::post('/pengajuans/{karyawan_id}', [PengajuanController::class, 
'store'])->name('pengajuans.store');

Route::get('/pengajuans/{karyawan_id}/edit', [PengajuanController::class,
'edit'])->name('pengajuans.edit');

Route::put('/pengajuans/{karyawan_id}', [PengajuanController::class, 
'update'])->name('pengajuans.update');

Route::delete('pengajuans/{karyawan_id}',[PengajuanController::class, 
'destroy'])->name('pengajuans.destroy');

// ini bagian dari link/url tampilan cuti
Route::get('/cutis',[CutiController::class, 
'index'])->middleware('auth')->name('cutis.index');

Route::get('/cutis/create',[CutiController::class, 
'create'])->name('cutis.create');

Route::post('/cutis',[CutiController::class, 
'store'])->name('cutis.store');

Route::get('/cutis/{cutis}/edit',[CutiController::class, 
'edit'])->name('cutis.edit');

Route::put('/cutis/{cutiss}',[CutiController::class, 
'update'])->name('cutis.update');

Route::delete('cutis/{cutis}',[CutiController::class, 
'destroy'])->name('cutis.destroy');

// ini bagian dari link/url tampilan register, login dan logout
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
Route::get('/pengajuans', [PengajuanController::class, 'index'])->name('pengajuans.index');

// // ini bagian dari link/url tampilan dashboard
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware('auth')->name('dashboard');

// protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('karyawans', KaryawanController::class);
    
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
});

// ini resource buat mengarahkan document
Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');   // Tampilkan semua dokumen
Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create'); // Form upload
Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');   // Simpan file
Route::get('/documents/{id}', [DocumentController::class, 'show'])->name('documents.show'); // Lihat PDF
Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy'); // Hapus file