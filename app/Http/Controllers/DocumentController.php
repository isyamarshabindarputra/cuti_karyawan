<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use PhpParser\Comment\Doc;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::all();
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dokumen' => 'required',
            'file_path' => 'required|mimes:pdf|max:2048',
        ]);

        
        // Simpan file ke storage/app/public/pdfs
        $path = $request->file('file_path')->store('file_document', 'public');

        Document::create([
            'nama_dokumen' => $request->nama_dokumen,
            'file_path' => $path,
        ]);

        return redirect()->route('documents.index')->with('success', 'File PDF berhasil diupload!');
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);
        return view('documents.show', compact('document'));
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        // Hapus file dari storage
        if (\Storage::disk('public')->exists($document->file_path)) {
            \Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();
        return redirect()->route('documents.index')->with('success', 'File berhasil dihapus!');
    }
}