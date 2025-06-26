<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use App\Models\Santri;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PerizinanController extends Controller
{
    // ... method lain (index, create, store, dll) tidak perlu diubah ...
    public function index(Request $request)
    {
        $query = Perizinan::with('santri');
        if ($request->filled('search')) {
            $query->whereHas('santri', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('month')) {
            $query->whereMonth('tanggal_izin', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('tanggal_izin', $request->year);
        }
        $perizinans = $query->latest()->paginate(10);
        return view('perizinan.index', compact('perizinans'));
    }

    public function create() { return view('perizinan.create'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'kepentingan_izin' => 'required|string|max:255',
            'tanggal_izin' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_izin',
            'keterangan_tambahan' => 'nullable|string',
        ]);
        $validated['status'] = 'Izin';
        Perizinan::create($validated);
        return redirect()->route('perizinan.index')->with('success', 'Data perizinan berhasil ditambahkan.');
    }

    /**
     * PERBAIKAN UTAMA DI SINI
     */
    public function searchSantri(Request $request)
    {
        // Menggunakan 'q' sebagai parameter default dari kode original
        $searchTerm = $request->q;
        
        $santris = Santri::where(function ($query) use ($searchTerm) {
                        $query->where('nama_lengkap', 'LIKE', '%'.$searchTerm.'%')
                              ->orWhere('Id_santri', 'LIKE', '%'.$searchTerm.'%');
                    })
                    ->whereIn('status_santri', ['Aktif', 'Pengurus']) 
                    ->limit(10)
                    ->get();

        // Kita tambahkan properti baru 'foto_url' ke setiap santri sebelum dikirim
        $santris->transform(function ($santri) {
            $santri->foto_url = $santri->foto 
                                ? asset('storage/fotos/' . $santri->foto) 
                                : 'https://ui-avatars.com/api/?name=' . urlencode($santri->nama_lengkap) . '&color=7F9CF5&background=EBF4FF';
            return $santri;
        });

        return response()->json($santris);
    }
    
    // ... sisa method lain tidak perlu diubah ...
    public function show(Perizinan $perizinan) { return view('perizinan.show', compact('perizinan')); }
    public function edit(Perizinan $perizinan) { return view('perizinan.edit', compact('perizinan')); }
    public function update(Request $request, Perizinan $perizinan) {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'kepentingan_izin' => 'required|string|max:255',
            'tanggal_izin' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_izin',
            'status' => 'required|in:Izin,Kembali,Terlambat',
            'keterangan_tambahan' => 'nullable|string',
        ]);
        $perizinan->update($validated);
        return redirect()->route('perizinan.index')->with('success', 'Data perizinan berhasil diperbarui.');
    }
    public function destroy(Perizinan $perizinan) { $perizinan->delete(); return redirect()->route('perizinan.index')->with('success', 'Data perizinan berhasil dihapus.'); }
    public function cetakDetailPdf(Perizinan $perizinan) { $pdf = Pdf::loadView('perizinan.detail_pdf', compact('perizinan')); return $pdf->download('surat-izin-' . $perizinan->santri->nama_lengkap . '.pdf'); }
    public function cetakBrowser(Perizinan $perizinan) { return view('perizinan.print', compact('perizinan')); }
}