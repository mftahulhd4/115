<?php

namespace App\Http\Controllers;

use App\Models\JenisPerizinan;
use Illuminate\Http\Request;

class JenisPerizinanController extends Controller
{
    /**
     * Menampilkan daftar semua jenis perizinan.
     */
    public function index()
    {
        $jenisPerizinan = JenisPerizinan::latest()->paginate(10);
        return view('jenisperizinan.index', compact('jenisPerizinan'));
    }

    /**
     * Menampilkan form untuk membuat jenis perizinan baru.
     */
    public function create()
    {
        return view('jenisperizinan.create');
    }

    /**
     * Menyimpan data jenis perizinan yang baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_perizinans,nama',
        ]);

        JenisPerizinan::create($request->all());

        return redirect()->route('master.jenis-perizinan.index')
            ->with('success', 'Jenis Perizinan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit jenis perizinan.
     */
    public function edit(JenisPerizinan $jenisPerizinan)
    {
        return view('jenisperizinan.edit', compact('jenisPerizinan'));
    }

    /**
     * Memperbarui data jenis perizinan di database.
     */
    public function update(Request $request, JenisPerizinan $jenisPerizinan)
    {
        // 1. Validasi input, pastikan nama boleh sama dengan data yang sedang diedit
        $request->validate([
            // ================================================================= //
            //           BAGIAN YANG DIPERBAIKI ADA DI BARIS INI                   //
            // ================================================================= //
            'nama' => 'required|string|max:255|unique:jenis_perizinans,nama,' . $jenisPerizinan->getKey() . ',id_jenis_perizinan',
        ]);

        // 2. Update data di database
        $jenisPerizinan->update($request->all());

        // 3. Alihkan kembali ke halaman utama dengan pesan sukses
        return redirect()->route('master.jenis-perizinan.index')
            ->with('success', 'Jenis Perizinan berhasil diperbarui.');
    }

    /**
     * Menghapus data jenis perizinan dari database.
     */
    public function destroy(JenisPerizinan $jenisPerizinan)
    {
        $jenisPerizinan->delete();

        return redirect()->route('master.jenis-perizinan.index')
            ->with('success', 'Jenis Perizinan berhasil dihapus.');
    }
}