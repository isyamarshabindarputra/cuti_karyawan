@extends('layouts.app')

@section('content')
<h2>Ajukan Cuti</h2>

<form action="{{ route('pengajuans.store', $karyawans->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label>Jenis Cuti</label>
        <select name="jenis_cuti" class="form-control" required>
            <option value="Tahunan">Tahunan</option>
            <option value="Sakit">Sakit</option>
            <option value="Melahirkan">Melahirkan</option>
            <option value="Cuti Penting">Cuti Penting</option>
            <option value="Cuti Besar">Cuti Besar</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" class="form-control" required>
    </div>

    <div class="mb-2">
        <label>keterangan</label>
        <textarea name="keterangan" id="keterangan"></textarea>
    </div>

    <button type="submit" class="btn btn-success">Ajukan</button>
    <a href="{{ route('pengajuans.index') }}" class="btn btn-secondary">Kembali</a>
</form>


@endsection
