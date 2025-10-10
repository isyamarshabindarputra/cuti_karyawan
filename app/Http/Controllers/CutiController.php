<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class CutiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Cuti::query();

        // kalau ada pencarian
        if($request->has('search') && $request->search != ''){
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%')
            ->orWhere('jenis_cuti', 'like', '%' . $request->search . '%')
            ->orWhere('bidang', 'like', '%' . $request->search . '%');
        }
        
        $cutis = $query->paginate(10);
        return view('cutis.index', compact('cutis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $karyawans = Karyawan::findOrFail($request->karyawan_id);
        return view('cutis.create', compact('karyawans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_cuti' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
            'bidang' => 'required|in:Sekre,TIK,Stasan,PT',
        ]);

        $karyawans = Karyawan::findOrFail($request->karyawan_id);

        Cuti::create([
            'karyawan_id' =>$karyawans->id,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_cuti' => $request->jenis_cuti,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan,
            'bidang' => $request->bidang,
        ]);

        return redirect()->route('cutis.index')
                        ->with('success', 'Data cuti berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $cutis = Cuti::findOrFail($id);
        return view('cutis.edit', compact('cutis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_cuti' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
            'bidang' => 'required|in:Sekre,TIK,Stasan,PT',
        ]);

        $cutis = Cuti::findOrFail($id);
        $cutis->update($request->all());

        return redirect()->route('cutis.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function destroy(Cuti $cutis)
    {
        $cutis->delete();
        return redirect()->route('cutis.index')
                        ->with('success', 'Data cuti berhasil dihapus.');
    }
}