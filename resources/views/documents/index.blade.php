<!DOCTYPE html>
<html>
<head>
    <title>Daftar PDF</title>
</head>
<body>
<h2>Daftar File PDF</h2>

<a href="{{ route('documents.create') }}">Upload Baru</a>
<br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>No</th>
        <th>nama dokumen</th>
        <th>File</th>
        <th>Aksi</th>
    </tr>
    @foreach($documents as $i => $doc)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $doc->nama_dokumen }}</td>
            <td>
                <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank">Download</a>
                <iframe src="{{ asset('storage/'.$document->file_path) }}"></iframe>
            </td>
            <td>
                <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Yakin hapus file ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>

</body>
</html>