<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        // ini buat mengambil query dari table karyawan
        $query = Karyawan::query()->where('user_id', Auth::id());

        // kalau ada pencarian 
        if($request->has('search') && $request->search != ''){
            $query->where('name', 'like', '%' . $request->search . '%')
            ->orWhere('nip', 'like', '%' . $request->search . '%')
            ->orWhere('jabatan', 'like', '%' . $request->search . '%')
            ->orWhere('bidang', 'like', '%' . $request->search . '%');
        }

        // jika bukan admin, batasi hanya karyawan milik user yang login
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
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

        // authorize: only admin or owner
        if (!Auth::user() || (!Auth::user()->isAdmin() && $karyawans->user_id !== Auth::id())) {
            abort(403);
        }

        return view('karyawans.show', compact('karyawans'));
    }

    public function edit(Karyawan $karyawans)
    {
        // load users for transfer dropdown if authenticated
        $users = [];
        if (Auth::user()) {
            $users = \App\Models\User::orderBy('name')->get();
        }

        // authorize edit: only admin or owner
        if (!Auth::user() || (!Auth::user()->isAdmin() && $karyawans->user_id !== Auth::id())) {
            abort(403);
        }

        return view('karyawans.edit', compact('karyawans','users'));
    }

    public function update(Request $request, Karyawan $karyawans)
    {
        // authorize update
        if (!Auth::user() || (!Auth::user()->isAdmin() && $karyawans->user_id !== Auth::id())) {
            abort(403);
        }

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

    // transfer ownership (owner or admin)
    public function transfer(Request $request, Karyawan $karyawans)
    {
        // allow if admin or owner
        if (!Auth::user() || (!Auth::user()->isAdmin() && $karyawans->user_id !== Auth::id())) {
            abort(403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $karyawans->update(['user_id' => $request->user_id]);

        return redirect()->route('karyawans.edit', $karyawans)->with('success', 'Karyawan berhasil dipindahkan ke akun baru.');
    }

    public function destroy(Karyawan $karyawans): RedirectResponse
    {
        // authorize delete
        if (!Auth::user() || (!Auth::user()->isAdmin() && $karyawans->user_id !== Auth::id())) {
            abort(403);
        }

        $karyawans->delete();

        return redirect()->route('karyawans.index')
            ->with('success', 'Data karyawan berhasil dihapus.');
    }
}