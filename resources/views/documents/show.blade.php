<!DOCTYPE html>
<html>
<head>
    <title>Lihat File PDF</title>
    <style>
        iframe {
            width: 100%;
            height: 90vh;
            border: none;
        }
    </style>
</head>
<body>

<h2>{{ $document->judul }}</h2>
<p><a href="{{ route('documents.index') }}">← Kembali</a></p>

<iframe src="{{ asset('storage/'.$document->file_path) }}"></iframe>
</body>
</html>