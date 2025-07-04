<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar semua data kelas.
     */
    public function index()
    {
        $kelass = Kelas::latest()->paginate(10);
        return view('kelas.index', compact('kelass'));
    }

    /**
     * Menampilkan form untuk membuat data kelas baru.
     */
    public function create()
    {
        return view('kelas.create');
    }

    /**
     * Menyimpan data kelas baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|unique:kelas,nama_kelas|max:255',
        ]);

        Kelas::create($validated);

        return redirect()->route('master.kelas.index')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data kelas.
     */
    public function edit(Kelas $kela) // Laravel akan otomatis resolve 'kela' menjadi objek Kelas
    {
        return view('kelas.edit', ['kelas' => $kela]);
    }

    /**
     * Mengupdate data kelas di database.
     */
    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|unique:kelas,nama_kelas,' . $kela->id_kelas . ',id_kelas|max:255',
        ]);

        $kela->update($validated);

        return redirect()->route('master.kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Menghapus data kelas dari database.
     */
    public function destroy(Kelas $kela)
    {
        // Cek apakah kelas ini masih digunakan oleh santri
        if ($kela->santris()->exists()) {
            return back()->with('error', 'Data kelas tidak dapat dihapus karena masih digunakan oleh santri.');
        }

        $kela->delete();

        return redirect()->route('master.kelas.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}