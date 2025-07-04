<?php

namespace App\Http\Controllers;

use App\Models\Pendidikan;
use Illuminate\Http\Request;

class PendidikanController extends Controller
{
    /**
     * Menampilkan daftar semua data pendidikan.
     */
    public function index()
    {
        $pendidikans = Pendidikan::latest()->paginate(10);
        return view('pendidikan.index', compact('pendidikans'));
    }

    /**
     * Menampilkan form untuk membuat data pendidikan baru.
     */
    public function create()
    {
        return view('pendidikan.create');
    }

    /**
     * Menyimpan data pendidikan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pendidikan' => 'required|string|unique:pendidikans,nama_pendidikan|max:255',
        ]);

        Pendidikan::create($validated);

        // PERBAIKAN DI SINI
        return redirect()->route('master.pendidikan.index')->with('success', 'Data pendidikan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data pendidikan.
     */
    public function edit(Pendidikan $pendidikan)
    {
        return view('pendidikan.edit', compact('pendidikan'));
    }

    /**
     * Mengupdate data pendidikan di database.
     */
    public function update(Request $request, Pendidikan $pendidikan)
    {
        $validated = $request->validate([
            'nama_pendidikan' => 'required|string|unique:pendidikans,nama_pendidikan,' . $pendidikan->id_pendidikan . ',id_pendidikan|max:255',
        ]);

        $pendidikan->update($validated);

        // PERBAIKAN DI SINI
        return redirect()->route('master.pendidikan.index')->with('success', 'Data pendidikan berhasil diperbarui.');
    }

    /**
     * Menghapus data pendidikan dari database.
     */
    public function destroy(Pendidikan $pendidikan)
    {
        if ($pendidikan->santris()->exists()) {
            // PERBAIKAN DI SINI
            return back()->with('error', 'Data pendidikan tidak dapat dihapus karena masih digunakan oleh santri.');
        }

        $pendidikan->delete();

        // PERBAIKAN DI SINI
        return redirect()->route('master.pendidikan.index')->with('success', 'Data pendidikan berhasil dihapus.');
    }
}