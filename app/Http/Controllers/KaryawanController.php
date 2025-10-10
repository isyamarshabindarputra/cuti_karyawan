<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        // ini buat mengambil query dari table karyawan
        $query = Karyawan::query();

        // kalau ada pencarian 
        if($request->has('search') && $request->search != ''){
            $query->where('name', 'like', '%' . $request->search . '%')
            ->orWhere('jabatan', 'like', '%' . $request->search . '%')
            ->orWhere('bidang', 'like', '%' . $request->search . '%');
        }

        $karyawans = $query->paginate(10);
        return view('karyawans.index', compact('karyawans'));
    }

    public function create()
    {
        return view('karyawans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'nullable|max:50',
            'name' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'jabatan' => 'nullable|max:100',
            'bidang' => 'required|in:Sekre,TIK,Stasan,PT',
        ]);

        
        Karyawan::create(array_merge($request->all(), ['user_id' =>Auth::id()]));

        return redirect()->route('karyawans.index')
            ->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $karyawans = \App\Models\Karyawan::with('pengajuan')->findOrFail($id);
        return view('karyawans.show', compact('karyawans'));
    }

    public function edit(Karyawan $karyawans)
    {
        return view('karyawans.edit', compact('karyawans'));
    }

    public function update(Request $request, Karyawan $karyawans)
    {
        $request->validate([
            'nip' => 'nullable|max:50',
            'name' => 'required|string|max:255',
            'no_telepon' => 'nullable|max:20',
            'jabatan' => 'nullable|max:100',
            'bidang' => 'required|in:Sekre,TIK,Stasan,PT',
        ]);

        $karyawans->update($request->all());

        return redirect()->route('karyawans.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawans): RedirectResponse
    {
        $karyawans->delete();

        return redirect()->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil dihapus.');
    }
}