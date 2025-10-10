<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pengajuan::query();

        // kalau ada pencarian
        if($request->has('search') && $request->search != ''){
            $query->where('jenis_cuti', 'like', '%' . $request->search . '%')
            ->orWhere('keterangan', 'like', '%' . $request->search . '%');
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

        if ($karyawan->sisa_cuti <= 0) {
        return redirect()->route('karyawans.index')
            ->with('error', 'Sisa cuti ini sudah habis');
        }
        return view('pengajuans.create', compact('karyawans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_cuti' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
        ]);

        
        $mulai = Carbon::parse($request->tanggal_mulai);
        $selesai = Carbon::parse($request->tanggal_selesai);
        $jumlah_hari = $mulai->diffInDays($selesai) + 1; // +1 supaya termasuk hari mulai dan selesai
        
        $karyawans = Karyawan::findOrFail($request->karyawan_id);

        if ($karyawans->sisa_cuti < $jumlah_hari) {
            return redirect()->back()->withErrors(['error' => 'Sisa pengajuan cuti tidak mencukupi.']);
        }

        Pengajuan::create([
            'karyawan_id' =>$karyawans->id,
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
        $pengajuans = Pengajuan::findOrFail($id);
        return view('pengajuans.edit', compact('pengajuans'));
    }

    /**
     * Display the specified resource.
     */
    public function update(Request $request, $id)
    {
        // ambil data pengajuan dan karyawan terkait
        $pengajuans = Pengajuan::findOrFail($id);
        $karyawans = $pengajuans->karyawan;

        $request->validate([
            'jenis_cuti' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
        ]);

        // hitung ulang jumlah hari
        $mulai = Carbon::parse($request->tanggal_mulai);
        $selesai = Carbon::parse($request->tanggal_selesai);
        $jumlahHariBaru = $mulai->diffInDays($selesai) + 1; // +1 supaya termasuk hari mulai dan selesai

        // kembalikan sisa cuti sebelumnya
        $karyawans->increment('sisa_cuti', $pengajuans->jumlah_hari);

        // jika karyawan diubah, ambil data karyawan baru
        if ($karyawans->id != $request->karyawan_id) {
            $karyawans = Karyawan::findOrFail($request->karyawan_id);
        }

        // cek sisa cuti cukup
        if ($karyawans->sisa_cuti < $jumlahHariBaru) {
            return redirect()->back()->withErrors(['error' => 'Sisa cuti tidak mencukupi.']);
        }

        // update pengajuan
        $pengajuans->update([
            'karyawan_id' => $karyawans->id,
            'jenis_cuti' => $request->jenis_cuti,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jumlah_hari' => $jumlahHariBaru,
            'keterangan' => $request->keterangan,
        ]);

        // kurangi sisa cuti sesuai jumlah hari baru
        $karyawans->decrement('sisa_cuti', $jumlahHariBaru);

        return redirect()->route('pengajuans.index')->with('success', 'Data pengajuan cuti berhasil diperbarui.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function destroy($id)
    {
        $pengajuans = Pengajuan::findOrFail($id);
        $karyawans = $pengajuans->karyawan;
        
        // kembalikan sisa cuti jika pengajuan dihapus
        $karyawans->increment('sisa_cuti', $pengajuans->jumlah_hari);
        $pengajuans->delete(); 

        return redirect()->route('pengajuans.index')
                        ->with('success', 'Data pengajuan cuti berhasil dihapus.');
    }
}