<!DOCTYPE html>
<html>
<head>
    <title>Upload PDF</title>
</head>
<body>
<h2>Upload File PDF</h2>

<form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label>Judul:</label><br>
    <input type="text" name="nama_dokumen"><br><br>

    <label>File PDF:</label><br>
    <input type="file" name="file_path" accept="application/pdf" required><br><br>

    <button type="submit">Upload</button>
</form>

<a href="{{ route('documents.index') }}">Lihat Daftar File</a>
</body>
</html>