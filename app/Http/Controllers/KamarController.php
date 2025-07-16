<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    /**
     * Menerapkan Gate 'is-admin' ke semua method di controller ini.
     */
    public function __construct()
    {
        $this->middleware('can:is-admin');
    }

    /**
     * Menampilkan daftar semua data kamar.
     */
    public function index()
    {
        $kamars = Kamar::latest()->paginate(10);
        return view('kamar.index', compact('kamars'));
    }

    /**
     * Menampilkan form untuk membuat data kamar baru.
     */
    public function create()
    {
        return view('kamar.create');
    }

    /**
     * Menyimpan data kamar baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kamar' => 'required|string|unique:kamars,nama_kamar|max:255',
        ]);

        Kamar::create($validated);

        return redirect()->route('master.kamar.index')->with('success', 'Data kamar berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data kamar.
     * @param Kamar $kamar
     */
    public function edit(Kamar $kamar)
    {
        return view('kamar.edit', compact('kamar'));
    }

    /**
     * Mengupdate data kamar di database.
     * @param Request $request
     * @param Kamar $kamar
     */
    public function update(Request $request, Kamar $kamar)
    {
        $validated = $request->validate([
            'nama_kamar' => 'required|string|unique:kamars,nama_kamar,' . $kamar->id_kamar . ',id_kamar|max:255',
        ]);

        $kamar->update($validated);

        return redirect()->route('master.kamar.index')->with('success', 'Data kamar berhasil diperbarui.');
    }

    /**
     * Menghapus data kamar dari database.
     * @param Kamar $kamar
     */
    public function destroy(Kamar $kamar)
    {
        // Cek apakah kamar masih digunakan oleh santri
        if ($kamar->santris()->exists()) {
            return back()->with('error', 'Data kamar tidak dapat dihapus karena masih digunakan oleh santri.');
        }

        $kamar->delete();

        return redirect()->route('master.kamar.index')->with('success', 'Data kamar berhasil dihapus.');
    }
}