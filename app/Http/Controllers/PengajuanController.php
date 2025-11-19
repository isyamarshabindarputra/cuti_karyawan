<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pengajuan::with('karyawan');

        // kalau ada pencarian
        if($request->has('search') && $request->search != ''){
            $search = '%' . $request->search . '%';
            $query->where(function($q) use ($search) {
                $q->where('jenis_cuti', 'like', $search)
                  ->orWhere('keterangan', 'like', $search)
                  ->orWhereHas('karyawan', function($qq) use ($search) {
                      $qq->where('name', 'like', $search)
                         ->orWhere('nip', 'like', $search);
                  });
            });
        }

        // jika bukan admin, batasi hanya pengajuan milik karyawan yang dimiliki user
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            $query->whereHas('karyawan', function($q) {
                $q->where('user_id', Auth::id());
            });
        }

        $pengajuans = $query->paginate(10);
        return view('pengajuans.index', compact('pengajuans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $karyawans = Karyawan::findOrFail($request->karyawan_id);

        $karyawan = \App\Models\Karyawan::findOrFail($request->karyawan_id);

        if ($karyawans->sisa_cuti <= 0) {
            return redirect()->route('karyawans.index')->with('error', 'Sisa cutimu sudah habis.');
        }
        return view('pengajuans.create', compact('karyawans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $karyawans = Karyawan::findOrFail($request->karyawan_id);
        
        $request->validate([
            'jenis_cuti' => 'required|in:Tahunan,Sakit,Melahirkan,Penting',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
        ]);

        
        $mulai = Carbon::parse($request->tanggal_mulai);
        $selesai = Carbon::parse($request->tanggal_selesai);
        $jumlah_hari = $mulai->diffInDays($selesai) + 1; // +1 supaya termasuk hari mulai dan selesai

        if ($karyawans->sisa_cuti < $jumlah_hari) {
            return redirect()->back()->withErrors(['error' => 'Sisa pengajuan cuti tidak mencukupi.']);
        }

        Pengajuan::create([
            'karyawan_id' => $karyawans->id,
            'user_id' => Auth::id(), // simpan user id
            'jenis_cuti' => $request->jenis_cuti,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jumlah_hari' => $jumlah_hari,
            'keterangan' => $request->keterangan,
        ]);

        $karyawans->decrement('sisa_cuti', $jumlah_hari);

        return redirect()->route('pengajuans.index')
                        ->with('success', 'Data pengajuan cuti berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // ambil data pengajuan dan karyawan terkait
        $pengajuans = Pengajuan::findOrFail($id);
        // ambil daftar karyawan untuk dipilih saat edit
        $karyawans = Karyawan::all();
        return view('pengajuans.edit', compact('pengajuans', 'karyawans'));
    }

    /**
     * Display the specified resource.
     */
    public function update(Request $request, $id)
    {
        // ambil data pengajuan dan karyawan terkait
        $pengajuans = Pengajuan::findOrFail($id);

        $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'jenis_cuti' => 'required|in:Tahunan,Sakit,Melahirkan,Penting',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
        ]);

        // authorization: pastikan pemilik pengajuan adalah user yang login
        if ($pengajuans->user_id !== Auth::id()) {
            abort(403);
        }

        // hitung ulang jumlah hari
        $mulai = Carbon::parse($request->tanggal_mulai);
        $selesai = Carbon::parse($request->tanggal_selesai);
        $jumlahHariBaru = $mulai->diffInDays($selesai) + 1; // +1 supaya termasuk hari mulai dan selesai

        // kembalikan sisa cuti pada karyawan lama terlebih dahulu
        $karyawanLama = $pengajuans->karyawan;
        if ($karyawanLama) {
            $karyawanLama->increment('sisa_cuti', $pengajuans->jumlah_hari);
        }

        // ambil karyawan baru (bisa sama dengan lama)
        $karyawanBaru = Karyawan::findOrFail($request->karyawan_id);

        // cek sisa cuti cukup pada karyawan baru
        if ($karyawanBaru->sisa_cuti < $jumlahHariBaru) {
            // rollback: kembalikan pengurangan pada karyawan lama sudah dilakukan, tapi jika gagal beri pesan
            return redirect()->back()->withErrors(['error' => 'Sisa cuti tidak mencukupi pada karyawan yang dipilih.']);
        }

        // update pengajuan, pastikan user_id mengikuti user dari karyawan yang dipilih
        $pengajuans->update([
            'karyawan_id' => $karyawanBaru->id,
            'user_id' => $karyawanBaru->user_id,
            'jenis_cuti' => $request->jenis_cuti,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jumlah_hari' => $jumlahHariBaru,
            'keterangan' => $request->keterangan,
        ]);

        // kurangi sisa cuti sesuai jumlah hari baru pada karyawan baru
        $karyawanBaru->decrement('sisa_cuti', $jumlahHariBaru);

        return redirect()->route('pengajuans.index')->with('success', 'Data pengajuan cuti berhasil diperbarui.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function destroy($id)
    {
        $pengajuans = Pengajuan::findOrFail($id);
        
        // pastikan pemilik pengajuan adalah user yang login
        if ($pengajuans->user_id !== Auth::id()) {
            abort(403);
        }

        $karyawans = $pengajuans->karyawan;
        
        // kembalikan sisa cuti jika pengajuan dihapus
        $karyawans->increment('sisa_cuti', $pengajuans->jumlah_hari);
        $pengajuans->delete(); 

        return redirect()->route('pengajuans.index')
                        ->with('success', 'Data pengajuan cuti berhasil dihapus.');
    }
}