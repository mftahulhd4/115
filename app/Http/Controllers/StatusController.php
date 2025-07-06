<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Menerapkan Gate 'is-admin' ke semua method di controller ini.
     */
    public function __construct()
    {
        $this->middleware('can:is-admin');
    }

    /**
     * Menampilkan daftar semua data status.
     */
    public function index()
    {
        $statuses = Status::latest()->paginate(10);
        return view('status.index', compact('statuses'));
    }

    /**
     * Menampilkan form untuk membuat data status baru.
     */
    public function create()
    {
        return view('status.create');
    }

    /**
     * Menyimpan data status baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_status' => 'required|string|unique:statuses,nama_status|max:255',
        ]);

        Status::create($validated);

        return redirect()->route('master.status.index')->with('success', 'Data status berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data status.
     */
    public function edit(Status $status)
    {
        return view('status.edit', compact('status'));
    }

    /**
     * Mengupdate data status di database.
     */
    public function update(Request $request, Status $status)
    {
        $validated = $request->validate([
            'nama_status' => 'required|string|unique:statuses,nama_status,' . $status->id_status . ',id_status|max:255',
        ]);

        $status->update($validated);

        return redirect()->route('master.status.index')->with('success', 'Data status berhasil diperbarui.');
    }

    /**
     * Menghapus data status dari database.
     */
    public function destroy(Status $status)
    {
        // Cek apakah status ini masih digunakan oleh santri
        if ($status->santris()->exists()) {
            return back()->with('error', 'Data status tidak dapat dihapus karena masih digunakan oleh santri.');
        }

        $status->delete();

        return redirect()->route('master.status.index')->with('success', 'Data status berhasil dihapus.');
    }
}