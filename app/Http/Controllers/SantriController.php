<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SantriController extends Controller
{
    /**
     * Display a listing of the resource.
     * Ini adalah method yang hilang sebelumnya.
     */
    public function index(Request $request)
    {
        $query = Santri::latest();

        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status_santri')) {
            $query->where('status_santri', $request->status_santri);
        }

        $santris = $query->paginate(10)->withQueryString();

        return view('santri.index', compact('santris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('santri.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'nama_orang_tua' => 'required|string|max:255',
            'pendidikan' => 'required|string|max:255',
            'nomer_orang_tua' => 'required|string|max:20',
            'tahun_masuk' => 'required|digits:4|integer|min:1900',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status_santri' => 'required|in:Aktif,Baru,Pengurus,Alumni',
        ]);

        $data = $request->except('foto');
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('santri-fotos', 'public');
        }

        Santri::create($data);
        return redirect()->route('santri.index')->with('success', 'Santri berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Santri $santri)
    {
        return view('santri.show', compact('santri'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Santri $santri)
    {
        return view('santri.edit', compact('santri'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Santri $santri)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'nama_orang_tua' => 'required|string|max:255',
            'pendidikan' => 'required|string|max:255',
            'nomer_orang_tua' => 'required|string|max:20',
            'tahun_masuk' => 'required|digits:4|integer|min:1900',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status_santri' => 'required|in:Aktif,Baru,Pengurus,Alumni',
            'tahun_keluar' => 'nullable|digits:4|integer|min:1900',
        ]);

        $data = $request->except('foto');
        if ($request->hasFile('foto')) {
            if ($santri->foto) {
                Storage::disk('public')->delete($santri->foto);
            }
            $data['foto'] = $request->file('foto')->store('santri-fotos', 'public');
        }

        $santri->update($data);
        return redirect()->route('santri.index')->with('success', 'Data santri berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Santri $santri)
    {
        if ($santri->foto) {
            Storage::disk('public')->delete($santri->foto);
        }
        $santri->delete();
        return redirect()->route('santri.index')->with('success', 'Data santri berhasil dihapus.');
    }

    /**
     * Generate PDF for all santri.
     */
    public function cetak_pdf()
    {
        $santris = Santri::all();
        $pdf = PDF::loadView('santri.pdf', ['santris' => $santris]);
        return $pdf->stream('laporan-santri.pdf');
    }

    /**
     * Generate PDF for a single santri detail.
     */
    public function cetakDetailPdf(Santri $santri)
    {
        $pdf = Pdf::loadView('santri.detail_pdf', ['santri' => $santri]);
        $fileName = 'detail-santri-' . Str::slug($santri->nama_lengkap) . '.pdf';
        return $pdf->download($fileName);
    }

    public function cetakBrowser(Santri $santri)
    {
        return view('santri.print', compact('santri'));
    }

}