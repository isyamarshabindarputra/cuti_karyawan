<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // eager load karyawan to avoid N+1 and scope to current user
        $query = Pengajuan::with('karyawan')->where('user_id', Auth::id());

        // kalau ada pencarian, cari di jenis_cuti, keterangan, atau nama karyawan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($pengajuan) use ($search) {
                $pengajuan->where('jenis_cuti', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('karyawan', function ($karyawan) use ($search) {
                      $karyawan->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $pengajuans = $query->paginate(10)->appends($request->only('search'));

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